#!/usr/bin/python3

import os
import mysql.connector
import sys

def verbindungsaufbau():
    cnx = mysql.connector.connect(user='root', password='', host='localhost', database='MitfahrerDB')
    
    return cnx
    
def stornierte_fahrt(cnx,name):
    name = 1
    cur = cnx.cursor()
    cur.execute(f"SELECT u.cemail FROM tuser u WHERE u.kid = {name}")
    row = cur.fetchall()
    list = []
    for i in range(len(row)):
        list.append(str(*row[i]))
    
    print(list)

args = sys.argv[1:]
args[0] = args[0].lower()

cnx = verbindungsaufbau()
stornierte_fahrt(cnx,*args)
