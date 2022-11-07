#!/usr/bin/python3
import logging
import importlib
import os
from ftplib import FTP
from glob import glob
import hashlib
from shutil import copyfile
import paramiko
from kunden import mainConfig
import cx_Oracle
import sys
import urllib

#Logger Configuration; Parameter:name des Loggers, Name des Log Files, LEVEL der Log Files
def setup_logger(name, log_file, level=logging.INFO):

    formatter = logging.Formatter('%(asctime)s %(levelname)s %(message)s') #asctime in richtger zeitfolge dokumentiert, levelname: dokemntation in textform(string), message: default valaue wenn keine Formatierungsform der Zeit angegeben wird
    handler = logging.FileHandler(log_file) #Name des Log Files
    handler.setFormatter(formatter) # hier übergeben wir, wie es in der datei aussehen soll

    logger = logging.getLogger(name) # bennenung der Datei
    logger.setLevel(level) # Info level übergeben
    logger.addHandler(handler) #

    return logger #rückgabe der Configuration um die Logger in der Main zu erstellen


def sendMail_kunde(config, bodyMail_kunde):
    #Fehleroptionen: 1.Verbindung zur Datenbank
                    #2.Select statement ist falsch
                    #3.falsche E-Mail
    try:

        dsn = cx_Oracle.makedsn("bert.dtvtabak.de", 1521, service_name="bertdb.dtvtabak.de")
        connection = cx_Oracle.connect("g_user", "G_user", dsn, encoding="UTF-8")

        cur = connection.cursor()
        cur.execute("SELECT person.e_mail FROM person,fghs,zwischentabelle WHERE person.fgh_id = fghs.fgh_id AND person.person_id = zwischentabelle.person_id AND zwischentabelle.abteilung_id = 1 AND person.vorname = 'Jens' AND lower(fghs.fgh_name) like  ""'"+config.nameDat+"'""")
        row = cur.fetchone()
        for i in range(len(row)):
            if row[i] == config.email_send[i]:
                os.system('echo "'+bodyMail_kunde+'" | mail -s "'+config.subject+'" '+ str(*row))
            else:
                os.system('echo "'+bodyMail_kunde+'" | mail -s "'+config.subject+'" '+ config.email_send)

    except Exception as e:
        mainConfig.mail_kunde = 1

    return mainConfig.mail_kunde

#sending Mail if Error occurs; Parameter: config Variable um die Variablen der momentanen Config Datei zu nutzen, alle Errors des Momentanen durchlaufs für die E-Mail
def sendMail(config, bodyMail,logger_error_mail):

    #Fehleroptionen  1.Verbindung zur Datenbank
                    #2.Select statement ist falsch
                    #3.falsche E-Mail
    try:
        dsn = cx_Oracle.makedsn("bert.dtvtabak.de", 1521, service_name="bertdb.dtvtabak.de")
        connection = cx_Oracle.connect("g_user", "g_user", dsn, encoding="UTF-8")

        cur = connection.cursor()
        cur.execute("SELECT person.e_mail FROM person,fghs,zwischentabelle WHERE person.fgh_id = fghs.fgh_id AND person.person_id = zwischentabelle.person_id AND fghs.fgh_nummer = 99 AND zwischentabelle.abteilung_id = 1 AND person.vorname = 'Jens'")
        row = cur.fetchone()
    except:
        os.system('echo "'+bodyMail+'" | mail -s "'+config.subject+'" '+ config.email_send_admin)
        #os.system('rm '+mainConfig.logname_log_errors_mail)

    try:
        for i in range(len(row)):
            if row[i] == config.email_send_admin:
                os.system('echo "'+bodyMail+'" | mail -s "'+config.subject+'" '+ str(*row))
            else:
                os.system('echo "'+bodyMail+'" | mail -s "'+config.subject+'" '+ config.email_send_admin)
        #os.system('rm '+mainConfig.logname_log_errors_mail)
    except Exception as e:
        mainConfig.mail = 1

    return mainConfig.mail
    
#importing ConfigFile; Parameter: Name des aktuellen Configs
def importConfig(name):
    #Fehleroptionen 1. Unterverzeichnis
                        #-falsch bennant
                        #nicht vorhanden

    try:
        config = importlib.import_module('kunden.'+name) # kunden = Subordner indem die Kunden Configs enthalten sind

        return config #Rückgabe des Configs um diese in anderen Funktionen nutzen zu können
    except Exception as e:
        mainConfig.impCon = 1

    return mainConfig.impCon

def connFTP(config,logger_info):
    #Fehleroptionen: 1.Host, Username und/oder Passwort können falsch service_name
    # oder die Variable ist nicht meehr vorhanden.
    try:
        ftp = FTP(config.host)
        ftp.login(config.username,config.password)
        logger_info.info('Connected with '+ config.host)

        return ftp # Rückgabe der ftp Verbindung um diese beim Download und Upload nutzen zu können
    except Exception as e:
        mainConfig.connFTP = 1
        #Local List; Parameter: extensions um zu wissen welchen datentyp man hochladen bzw. herunterladen soll, Config um die Variablen des aktuellen Configs nutzen zu können
    return mainConfig.connFTP
#Connecting to SFTP Server; Parameter: Config um die Variablen des aktuellen Configs nutzen zu können, logger_info um die richtig erfolgten Prozesse in der Log Datei zu dokumentieren
def connSFTP(config,logger_info):
    #Fehleroptionen 1. Pfad zum SSH-KeyError
                        #-SSH-Key vorhanden?
                    #2.Host und/oder Username ist falsch
    try:
        k = paramiko.RSAKey.from_private_key_file(config.ssh_key_filepath) #SSH KEY
        c = paramiko.SSHClient()
        c.set_missing_host_key_policy(paramiko.AutoAddPolicy())
        c.connect( hostname = config.host, username = config.username, pkey = k )
        sftp = c.open_sftp()
        logger_info.info('Connected with '+ config.host)

        return sftp # Rückgabe der sftp Verbindung um diese beim Download und Upload nutzen zu können
    except Exception as e:
        mainConfig.connSFTP = 1

    return mainConfig.connSFTP
#Connecting to FTP Server; Parameter: Config um die Variablen des aktuellen Configs nutzen zu können, logger_info um die richtig erfolgten Prozesse in der Log Datei zu dokumentieren
def listFiles_Local(config, logger_info):
    #Fehleroptionen 1. Fehlerhafter output_local Verzeichnis
    try:
        list = os.listdir(config.output_local)
        logger_info.info('List of ' + config.output_local)
        return list #rückgabe der Lokalen Liste zum Hochladen der enthaltenen Dateien
    except Exception as e:
        mainConfig.lLocal = 1

    return mainConfig.lLocal
#List of Remote Server Files; SFTP; Parameter: sftp Verbindung, welche art von datentypen die in die Liste soll, momentanen Config
def listFiles_remoteSFTP(sftp,config, logger_info):
    #Fehleroptionen 1. Fehlerhafter output_remote Verzeichnis
    try:
        list = sftp.listdir(config.output_remote)
        logger_info.info('List of ' + config.output_remote)
        return list #Rückgabe der Remote Liste für den SFTP
    except Exception as e:
        mainConfig.lRemoteSFTP = 1

    return mainConfig.lRemoteSFTP

def listFiles_remoteSFTP_tmp(sftp,config, logger_info):
    #Fehleroptionen 1. Fehlerhafter output_remote Verzeichnis
    try:
        list = sftp.listdir(config.tmp_remote)
        logger_info.info('List of ' + config.output_remote)
        return list #Rückgabe der Remote Liste für den SFTP
    except Exception as e:
        mainConfig.lRemoteSFTP = 1

    return mainConfig.lRemoteSFTP

def listFiles_remoteFTP_tmp(ftp,config, logger_info):
    #Fehleroptionen 1. Fehlerhafter output_remote Verzeichnis
    try:
        ftp.cwd(config.tmp_remote) #muss erstmal auf das verzeichnis rüber damit eine liste erstellt werden kann
        list = ftp.nlst()
        logger_info.info('List of ' + config.output_remote)
        return list #Rückgabe der Remote Liste für den FTP
    except Exception as e:
        mainConfig.lRemoteFTP = 1

    return mainConfig.lRemoteFTP

#List of Remote Server Files;FTP; Parameter: ftp Verbindung, welche art von datentypen die in die Liste soll, momentanen Config
def listFiles_remoteFTP(ftp,config, logger_info):
    #Fehleroptionen 1. Fehlerhafter output_remote Verzeichnis
    try:
        ftp.cwd(config.output_remote) #muss erstmal auf das verzeichnis rüber damit eine liste erstellt werden kann
        list = ftp.nlst()
        logger_info.info('List of ' + config.tmp_remote)
        return list #Rückgabe der Remote Liste für den FTP
    except Exception as e:
        mainConfig.lRemoteFTP = 1

    return mainConfig.lRemoteFTP
#Upload of all Files in the Local List; SFTP; Parameter: sftp Verbindung, logger_info zur dokumentation, Locale Liste der Dateien
def uploadSFTP(sftp, config,logger_info,list_localSFTP):
    #Fehleroptionen 1. Rechte
    #               2. Pfade
    try:
        sftp.put(config.output_local + list_localSFTP, config.tmp_remote + list_localSFTP)
        logger_info.info('Upload '+ list_localSFTP +  ' from ' + config.output_local + ' to ' + config.tmp_remote + '.')
    except Exception as e:
        mainConfig.upSFTP = 1

    return mainConfig.upSFTP

#Upload of all Files in the Local List;FTP; Parameter: ftp Verbindung, logger_info zur dokumentation, Locale Liste der Dateien
def uploadFTP(ftp,config,logger_info,list_localFTP):
    #Fehleroptionen 1. Rechte
                    #2. Pfade
                    #3. Wechsel zum Pfad
    try:
        #os.chdir(config.output_local)
        #ftp.cwd(config.tmp_remote)
        ftp.storbinary('STOR '+config.tmp_remote+list_localFTP, open(config.output_local+ list_localFTP,'rb'))
        logger_info.info('Upload '+ list_localFTP +  ' from ' + config.output_local + ' to ' + config.tmp_remote + '.')

    except Exception as e:
        mainConfig.upFTP = 1

    return mainConfig.upFTP
#Download of all Files in the Remote List;SFTP; Parameter: sftp Verbindung, logger_info zur dokumentation, Remote Liste der Dateien
def downSFTP(sftp,config,logger_info,list_remoteSFTP):
    #Fehleroptionen 1. Rechte
                    #2. Pfade
    try:
        sftp.get(config.output_remote + list_remoteSFTP, config.input_local + list_remoteSFTP)
        logger_info.info('Download '+ list_remoteSFTP +  ' from ' + config.output_remote + ' to ' + config.input_local + '.')
    except Exception as e:
        mainConfig.downSFTP = 1

    return mainConfig.downSFTP
#Download of all Files in the Remote List;FTP; Parameter: sftp Verbindung, logger_info zur dokumentation, Locale Liste der Dateien
def downFTP(ftp,config,logger_info,list_remoteFTP):
    #Fehleroptionen 1. Rechte
                    #2. Pfade
                    #3. Wechsel zum Pfad
    try:
        os.chdir(config.input_local)

        localfile = open(list_remoteFTP,'wb')
        ftp.retrbinary('RETR '+list_remoteFTP, localfile.write)
        localfile.close()
        logger_info.info('Download '+ list_remoteFTP +  ' from ' + config.output_remote + ' to ' + config.input_local + '.')
    except Exception as e:
        mainConfig.downFTP = 1

    return mainConfig.downFTP
#Check if the Uploaded File was Successfully send;FTP
def upFTPHash(list_localFTP, ftp,config, logger_info,logger_error):
    #Fehleroptionen 1. Öffnung Dateien + Verzeichnis
                    #2.Verzeichnis falsch
                    #3.Rechte
    try:
        hasher_local = hashlib.md5() #hash object
        hasher_remote = hashlib.md5() #hash object
        deleteAll = 0   #controll variable for deleting after uploading/downloading
        #HashTest
        for i in range(3):
            ftp.cwd('..')
            ftp.cwd(config.tmp_remote)
            ftp.retrbinary('RETR %s' % list_localFTP, hasher_remote.update)
            with open(config.output_local + '/'+list_localFTP,'rb') as open_file_local: #Open the file to read it's bytes
                content_local = open_file_local.read() #puting the contents to a seperate variable so it can be worked with
                hasher_local.update(content_local)  #past it to the hash object with update()
            if hasher_local.hexdigest() == hasher_remote.hexdigest(): #hexdigest = return md5 CheckSum and makes it to the original hexa format
                logger_info.info('Hash Test of ' + list_localFTP + ' was Successful.')
                break
            else:
                deleteAll = 1

        if deleteAll == 0:
            ftp.cwd('..')
            ftp.cwd(config.input_remote)
            ftp.rename(config.tmp_remote + list_localFTP,config.input_remote + list_localFTP)
            print(config.output_local)
            os.rename(config.output_local+list_localFTP, config.safe_dir_local+'moved_'+list_localFTP)
        else:
            logger_error.error('Die Datei '+list_localFTP+' ist nicht richitg angekommen.')
    except Exception as e:
        mainConfig.upHashFTP = 1

    return mainConfig.upHashFTP

#Check if the Uploaded File was Successfully send;SFTP
def upSFTPHash(list_localSFTP, sftp, config,logger_info,logger_error):
    #Fehleroptionen 1. Öffnung Dateien + Verzeichnis
                    #2.Verzeichnis falsch
                    #3.Rechte
                    #4.Verzeichnis für die Verschiebung falsch
    try:
        hasher_local = hashlib.md5() #hash object
        hasher_remote = hashlib.md5() #hash object
        deleteAll = 0   #controll variable for deleting after uploading/downloading

            #sftp.put(config.output_local + file, config.tmp_remote + file)
                #HashTest
        for i in range(3):
            with open(config.output_local + list_localSFTP,'rb') as open_file_local: #Open the file to read it's bytes
                content_local = open_file_local.read() #puting the contents to a seperate variable so it can be worked with
                hasher_local.update(content_local)  #past it to the hash object with update()
            with sftp.open(config.tmp_remote + list_localSFTP,'rb') as open_file_remote:
                content_remote = open_file_remote.read()
                hasher_remote.update(content_remote)
            if hasher_local.hexdigest() == hasher_remote.hexdigest(): #hexdigest = return md5 CheckSum and makes it to the original hexa format
                logger_info.info('Hash Test of ' + list_localSFTP + ' was Successful.')
                break
            else:
                deleteAll = 1

        if deleteAll == 0:
            sftp.rename(config.tmp_remote+list_localSFTP, config.input_remote+list_localSFTP)
            os.rename(config.output_local+list_localSFTP, config.safe_dir_local+'moved_'+list_localSFTP)
        else:
            logger_error.error('Die Datei '+list_localSFTP+' ist nicht richitg angekommen.')
    except Exception as e:
        mainConfig.upHashSFTP = 1

    return mainConfig.upHashSFTP

#Check if the Downloaded Files was Successfully Downloaded;FTP
def downFTPHash(list_remoteFTP, ftp,config, logger_info,logger_error):
    #Fehleroptionen 1. Öffnung Dateien + Verzeichnis
                    #2.Verzeichnis falsch
                    #3.Rechte

    try:
        hasher_local = hashlib.md5() #hash object
        hasher_remote = hashlib.md5() #hash object
        deleteAll = 0   #controll variable for deleting after uploading/downloading

        for i in range(3):
            ftp.cwd(config.output_remote)
            ftp.retrbinary('RETR %s' % list_remoteFTP, hasher_remote.update)
            with open(config.input_local +list_remoteFTP, 'rb') as open_file_remote:
                content_local = open_file_remote.read()
                hasher_local.update(content_local)
            if hasher_local.hexdigest() == hasher_remote.hexdigest():
                logger_info.info('Hash Test of ' + list_remoteFTP + ' was Successful.')
                break
            else:
                logger_error.error('Die Datei '+list_remoteFTP+' ist nicht richitg angekommen.')
    except Exception as e:
        mainConfig.downHashFTP = 1

    return mainConfig.downHashFTP

def downSFTPHash(list_remoteSFTP, sftp,config, logger_info,logger_error):
    #Fehleroptionen 1. Öffnung Dateien + Verzeichnis
                    #2.Verzeichnis falsche
                    #3.Rechte
    try:
        hasher_local = hashlib.md5() #hash object
        hasher_remote = hashlib.md5() #hash object
        deleteAll = 0   #controll variable for deleting after uploading/downloading

            #HashTest
        for i in range(3):
            with open(config.input_local + list_remoteSFTP,'rb') as open_file_local: #Open the file to read it's bytes
                content_local = open_file_local.read() #puting the contents to a seperate variable so it can be worked with
                hasher_local.update(content_local)  #past it to the hash object with update()
            with sftp.open(config.output_remote + list_remoteSFTP,'rb') as open_file_remote:
                content_remote = open_file_remote.read()
                hasher_remote.update(content_remote)
            if hasher_local.hexdigest() == hasher_remote.hexdigest(): #hexdigest = return md5 CheckSum and makes it to the original hexa format
                logger_info.info('Hash Test of ' + list_remoteSFTP + ' was Successful.')
                break
            else:
                logger_error.error('Die Datei '+list_remoteSFTP+' ist nicht richitg angekommen.')
    except Exception as e:
        mainConfig.downHashSFTP = 1

    return mainConfig.downHashSFTP

def main():
    #Creation of first file logger
    logger_info = setup_logger('first_logger', mainConfig.logname_log)
    #Creation of second file logger
    logger_error = setup_logger('second_logger', mainConfig.logname_log_errors)

    args = sys.argv[1:]

    args[0] = args[0].lower()
    #args.lower()
    config = importConfig(*args)
    #Creation of third logger for mail
    logger_error_mail = setup_logger('third_logger', mainConfig.logname_log_errors_mail)
    #Creation of fourth logger
    logger_error_mail_kunde = setup_logger('fourth_logger', mainConfig.logname_log_errors_mail_kunde)

    try:
        if config.sftp ==0:
            sftp = connSFTP(config,logger_info)
            list_localSFTP = listFiles_Local(config,logger_info)
            list_remoteSFTP = listFiles_remoteSFTP(sftp,config,logger_info)
            for i in range(len(list_localSFTP)):
                uploadSFTP(sftp,config,logger_info,list_localSFTP[i-1])
                upSFTPHash(list_localSFTP[i-1],sftp, config,logger_info,logger_error)
            for i in range(len(list_remoteSFTP)):
                downSFTP(sftp,config,logger_info,list_remoteSFTP[i-1])
                downSFTPHash(list_remoteSFTP[i-1],sftp,config,logger_info,logger_error)
            logger_info.info('Quit Connection to '+config.host+'.\n' )
            os.system('rm '+mainConfig.logname_log_errors_mail)
            sftp.close()
            #logger_info.info('')

        elif config.sftp ==1:
            ftp = connFTP(config,logger_info)
            list_localFTP = listFiles_Local(config,logger_info)
            list_remoteFTP = listFiles_remoteFTP(ftp,config, logger_info)
            for i in range(len(list_localFTP)):
                uploadFTP(ftp,config,logger_info,list_localFTP[i -1])
                upFTPHash(list_localFTP[i - 1],ftp, config,logger_info,logger_error)
            for i in range(len(list_remoteFTP)):
                downFTP(ftp,config,logger_info,list_remoteFTP[i -1])
                downFTPHash(list_remoteFTP[i-1],ftp,config,logger_info,logger_error)
            logger_info.info('Quit Connection to '+config.host+'.')
            os.system('rm '+mainConfig.logname_log_errors_mail)
            ftp.close()
            #logger_info.info('')


    except (Exception,IndexError,PermissionError) as e:
        if mainConfig.connSFTP ==1:
            logger_error_mail.info('Fehler in der Function connSFTP() bei '+ config.fghname)
            logger_error_mail_kunde.info('Eine Verbindung konnte nicht aufgebaut werden' )
            body_kunde_SFTP = open(mainConfig.logname_log_errors_mail_kunde,'r')
            bodyMail_kunde_SFTP=body_kunde_SFTP.read()
            sendMail_kunde(config,bodyMail_kunde_SFTP)
            os.system('rm '+mainConfig.logname_log_errors_mail_kunde)
        elif mainConfig.connFTP ==1:
            logger_error_mail.info('Fehler in der Function connFTP() bei ' + config.fghname)
            logger_error_mail_kunde.info('Eine Verbindung konnte nicht aufgebaut werden bei ')
            body_kunde_FTP = open(mainConfig.logname_log_errors_mail_kunde,'r')
            bodyMail_kunde_FTP=body_kunde_FTP.read()
            sendMail_kunde(config,bodyMail_kunde_FTP)
            os.system('rm '+mainConfig.logname_log_errors_mail_kunde)
        elif mainConfig.mail_kunde == 1:
            logger_error_mail.info('Fehler in der Function sendMail_kunde() bei ' + config.fghname)
        elif mainConfig.mail == 1:
            logger_error_mail.info('Fehler in der Function sendMail() bei ' + config.fghname)
        elif mainConfig.impCon == 1:
            logger_error_mail.info('Fehler in der Function importConfig() bei ' + config.fghname)
        elif mainConfig.lLocal == 1:
            logger_error_mail.info('Fehler in der Function listFiles_Local() bei ' + config.fghname)
        elif mainConfig.lRemoteSFTP == 1:
            logger_error_mail.info('Fehler in der Function listFiles_remoteSFTP() bei ' + config.fghname)
        elif mainConfig.lRemoteFTP == 1:
            logger_error_mail.info('Fehler in der Function listFiles_remoteFTP() bei ' + config.fghname)
        elif mainConfig.upSFTP == 1:
            logger_error_mail.info('Fehler in der Function uploadSFTP() bei '  + config.fghname)
        elif mainConfig.upFTP == 1:
            logger_error_mail.info('Fehler in der Function uploadFTP() bei ' + config.fghname)
        elif mainConfig.downSFTP == 1:
            logger_error_mail.info('Fehler in der Function downSFTP() bei '+ config.fghname)
        elif mainConfig.downFTP == 1:
            logger_error_mail.info('Fehler in der Function downFTP() bei '+ config.fghname)
        elif mainConfig.upHashSFTP == 1:
            logger_error_mail.info('Fehler in der Function upSFTPHash() bei '+ config.fghname)
        elif mainConfig.upHashFTP == 1:
            logger_error_mail.info('Fehler in der Function upFTPHash() bei '+ config.fghname)
        elif mainConfig.downHashSFTP == 1:
            logger_error_mail.info('Fehler in der Function downSFTPHash() bei '+ config.fghname)
        elif mainConfig.downHashFTP == 1:
            logger_error_mail.info('Fehler in der Function downFTPHash() bei '+ config.fghname)

    logger_error.error('')
    #logger_error.exception(e)
    #logger_error_mail.exception(e)
    body = open(mainConfig.logname_log_errors_mail,'r')
    bodyMail=body.read()
    sendMail(config,bodyMail,logger_error_mail)

if __name__ == '__main__':
    main()
