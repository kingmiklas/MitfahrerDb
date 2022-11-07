#!/usr/bin/python3

import os
import cx_Oracle
import sys
import importlib

def importConfig(name):
    config = importlib.import_module('kunden.'+name)
    return config

def abfrage(config):
    dsn = cx_Oracle.makedsn("bert.dtvtabak.de", 1521, service_name="bertdb.dtvtabak.de")
    connection = cx_Oracle.connect("g_user", "G_user", dsn, encoding="UTF-8")

    cur = connection.cursor()
    cur.execute("SELECT person.e_mail FROM person,fghs,zwischentabelle WHERE person.fgh_id = fghs.fgh_id AND person.person_id = zwischentabelle.person_id AND zwischentabelle.abteilung_id = 1 AND person.nachname = 'Turi' OR person.fgh_id = fghs.fgh_id AND person.person_id = zwischentabelle.person_id AND zwischentabelle.abteilung_id = 1 AND person.nachname = 'Abdulkarim'")
    row = cur.fetchall()
    list = []
    for i in range(len(row)):
        list.append(str(*row[i]))
    a_file = open("/home/dtv/Skripte/mahmoud/kunden/"+ config.nameDat + ".py", "r")
    list_of_lines = a_file.readlines()
    list_of_lines[24] = "email_send = "+str(list)+" #Email vom Kunden\n"

    a_file = open("/home/dtv/Skripte/mahmoud/kunden/"+ config.nameDat + ".py", "w")
    a_file.writelines(list_of_lines)
    a_file.close()

args = sys.argv[1:]
args[0] = args[0].lower()
#args.lower()
config = importConfig(*args)
abfrage(config)

# def abfrage(config):
#     dsn = cx_Oracle.makedsn("bert.dtvtabak.de", 1521, service_name="bertdb.dtvtabak.de")
#     connection = cx_Oracle.connect("g_user", "G_user", dsn, encoding="UTF-8")
#
#     cur = connection.cursor()
#     cur.execute("SELECT person.e_mail FROM person,fghs,zwischentabelle WHERE person.fgh_id = fghs.fgh_id AND person.person_id = zwischentabelle.person_id AND zwischentabelle.abteilung_id = 1 AND person.nachname = 'Turi' OR person.fgh_id = fghs.fgh_id AND person.person_id = zwischentabelle.person_id AND zwischentabelle.abteilung_id = 1 AND person.nachname = 'Abdulkarim'")
#     row = list(cur.fetchall())
#     for i in range(len(row)):
#         print(row[i])
#         if str(*row[i]) != config.email_send[i]:
#             a_file = open("/home/dtv/Skripte/mahmoud/kunden/"+ config.nameDat + ".py", "r")
#             list_of_lines = a_file.readlines()
#             list_of_lines[24] = "email_send = ['"+str(*row[i])+"'] #Email vom Kunden\n"
#
#             a_file = open("/home/dtv/Skripte/mahmoud/kunden/"+ config.nameDat + ".py", "w")
#             a_file.writelines(list_of_lines)
#             a_file.close()
