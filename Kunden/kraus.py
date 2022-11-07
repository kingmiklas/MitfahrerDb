#!/usr/bin/python3

#connection + Upload/Download
sftp = 1
nameDat = 'kraus' # wird nicht gebraucht
host = 'do-test'
username = 'dtv'
password = 'ThinkPadX1'
#password = 'etwasFalsches'
fghname = 'DTV'
#paths
input_remote = '/input/kraus/'                            #path muss vom aktuellen Verzeichnis angegeben werden
output_remote = '/output/kraus/'                          #path muss vom aktuellen Verzeichnis angegeben werden
input_local = '/home/dtv/SFTP/Kunden/input/kraus/'
output_local = '/home/dtv/SFTP/Kunden/output/kraus/'
#input_remote ='/home/dtv/test/'             #für den fehler auskommenieren

tmp_remote = '/tmp/'

safe_dir_local = '/home/dtv/SFTP/safeDir/'

subject = username+'_error'
file_name = 'hall'
#send_mail
email_send = ['jens.turi@dtvtabak.de'] #Email vom Kunden
email_send_admin = 'jens.turi@dtvtabak.de'
