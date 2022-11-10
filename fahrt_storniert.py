#!/usr/bin/python3

import os
import mysql.connector
import sys
import smtplib, ssl
from email.message import EmailMessage

port = 465  # For SSL
smtp_server = "smtp.gmail.com"
sender_email = "mitfahrerdb@gmail.com"  # Enter your address
#ausfuehrendes Geraet

#Windows-Computer
#password = "fqosompobdatpbpv"

#Mac
password = "apcmcdggunpglljy"
def verbindungsaufbau():
    cnx = mysql.connector.connect(user='root', password='', host='localhost', database='MitfahrerDB', port = 3329)

    return cnx
    
def fahrt_storniert(cnx,name):

    cur = cnx.cursor()
    cur.execute("SELECT u.cEmail FROM tPostedRides pr, tuser u, tuserrides ur WHERE pr.kID = ur.kRide and ur.kUser = u.kID and pr.kID = "+name+" and pr.bIsStorniert = 1;")
    row = cur.fetchall()
    liste = []
    for i in range(len(row)):
        liste.append(str(*row[i]))
    
    return liste

def send_mail(liste):
    
    receiver_email = liste

    msg = EmailMessage()
    msg.set_content("Eine Fahrt von Ihnen wurde storniert.")
    msg['Subject'] = "MitfahrerDB stornierte Fahrt"
    msg['From'] = sender_email
    msg['To'] = receiver_email  
    
    context = ssl.create_default_context()
    with smtplib.SMTP_SSL(smtp_server, port, context=context) as server:
        server.login(sender_email, password)
        server.send_message(msg, from_addr=sender_email, to_addrs=receiver_email)
    
 

args = sys.argv[1:]
args[0] = args[0].lower()

cnx = verbindungsaufbau()
liste = fahrt_storniert(cnx,*args)
send_mail(liste)

