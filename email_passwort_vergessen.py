#!/usr/bin/python3

import os
import mysql.connector
import sys
import random

def verbindungsaufbau():
    cnx = mysql.connector.connect(user='root', password='', host='localhost', database='MitfahrerDB')
    
    return cnx
    
def random_number(n):
    range_start = 10**(n-1)
    range_end = (10**n)-1
    return random.randint(range_start, range_end)

def passwort_vergessen(cnx,name):

    cur = cnx.cursor()
    cur.execute(f"SELECT u.cemail FROM tuser u WHERE u.kid = {name}")
    row = cur.fetchall()
    list = []
    for i in range(len(row)):
        list.append(str(*row[i]))
    
    randomnumber = random_number(5)
    

args = sys.argv[1:]
args[0] = args[0].lower()

cnx = verbindungsaufbau()
passwort_vergessen(cnx,*args)
