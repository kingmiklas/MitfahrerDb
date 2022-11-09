#!/usr/bin/python3

import os
import mysql.connector
import sys
import random
import smtplib, ssl
from email.message import EmailMessage

port = 465  # For SSL
smtp_server = "smtp.gmail.com"
sender_email = "mitfahrerdb@gmail.com"  # Enter your address
#ausführendes Gerät

#Windows-Computer
password = "fqosompobdatpbpv" 

#Mac
#password = "apcmcdggunpglljy"

def verbindungsaufbau():
    cnx = mysql.connector.connect(user='root', password='', host='localhost', database='MitfahrerDB', port = 3329)
    
    return cnx
    
def random_number(n):
    range_start = 10**(n-1)
    range_end = (10**n)-1
    return random.randint(range_start, range_end)

def passwort_vergessen(cnx,name):

    cur = cnx.cursor()
    cur.execute(f"SELECT u.cemail FROM tuser u WHERE u.kid = {name}")
    row = cur.fetchall()
    liste = []
    for i in range(len(row)):
        liste.append(str(*row[i]))
    
    randomnumber = random_number(7)
    
    return randomnumber,liste
    
def send_mail(randomnumber,liste):
    
    receiver_email = liste

    msg = EmailMessage()
    msg.set_content(f"Ihr neues Passwaort lautet: {randomnumber}")
    msg['Subject'] = "Passwort vergessen"
    msg['From'] = sender_email
    msg['To'] = receiver_email  
    
    context = ssl.create_default_context()
    with smtplib.SMTP_SSL(smtp_server, port, context=context) as server:
        server.login(sender_email, password)
        server.send_message(msg, from_addr=sender_email, to_addrs=receiver_email)

args = sys.argv[1:]
args[0] = args[0].lower()

cnx = verbindungsaufbau()
randomnumber,liste = passwort_vergessen(cnx,*args)
send_mail(randomnumber,liste)