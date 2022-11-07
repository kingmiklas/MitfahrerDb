#!/usr/bin/python3

#connection + Upload/Download
sftp = 0
nameDat = 'dtv'
host = 'do-test'
username = 'dtv'
ssh_key_filepath = '/home/dtv/.ssh/id_rsa'
#ssh_key_filepath = '/home/dtv/id_rsa'
fghname = 'DTV'
#paths
input_remote ='/home/dtv/input/hall/'
output_remote = '/home/dtv/output/hall/'
input_local = '/home/dtv/SFTP/Kunden/input/hall/'
output_local = '/home/dtv/SFTP/Kunden/output/hall/'
#input_remote = '/home/input/' #fü den Fehler ausklammern

tmp_remote = '/home/dtv/tmp/'

safe_dir_local = '/home/dtv/SFTP/safeDir/'

subject = username+'_error'
file_name = 'hall'
#send_mail
email_send = ['jens.turi@dtvtabak.de'] #Email vom Kunden
email_send_admin = 'jens.turi@dtvtabak.de'
