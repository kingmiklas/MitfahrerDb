#!/usr/bin/python3

#Variuablen zur Error Kontrolle
mail_kunde = 0
mail = 0
impCon = 0
connSFTP = 0
connFTP = 0
lLocal = 0
lRemoteSFTP = 0
lTmpRemoteSFTP = 0
lTmpremoteFTP = 0
lRemoteFTP = 0
upSFTP = 0
upFTP = 0
downSFTP = 0
downFTP = 0
upHashSFTP = 0
upHashFTP = 0
downHashSFTP = 0
downHashFTP = 0

logname_log = '/home/dtv/SFTP/logs/info_Log.log'
logname_log_errors = '/home/dtv/SFTP/logs/onlyErrors.log'
logname_log_errors_mail = '/home/dtv/SFTP/logs/onlyErrorsMail.log'
logname_log_errors_mail_kunde = '/home/dtv/SFTP/logs/onlyErrorsMailKunde.log'
