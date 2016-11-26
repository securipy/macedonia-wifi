import MySQLdb
import subprocess
import time 
import paramiko
from scp import SCPClient

DB_HOST = 'localhost' 
DB_USER = 'granada' 
DB_PASS = 'granada' 
DB_NAME = 'granada' 
 
def run_query(query=''): 
    datos = [DB_HOST, DB_USER, DB_PASS, DB_NAME] 
 
    conn = MySQLdb.connect(*datos) # Conectar a la base de datos 
    cursor = conn.cursor()         # Crear un cursor 
    cursor.execute(query)          # Ejecutar una consulta 
 
    if query.upper().startswith('SELECT'): 
        data = cursor.fetchall()   # Traer los resultados de un select 
    else: 
        conn.commit()              # Hacer efectiva la escritura de datos 
        data = None 
 
    cursor.close()                 # Cerrar el cursor 
    conn.close()                   # Cerrar la conexion 

    return data


checkProcessActive = run_query('SELECT name,file FROM wifi_data WHERE process=2 or process=3')

if not checkProcessActive:



	toProcess =  run_query('SELECT name,file FROM wifi_data WHERE process=1 AND file != "" LIMIT 1 ')

	if toProcess:
#		for x in toProcess:
 		
		query = "UPDATE wifi_data SET process=2 WHERE file='%s'" % (toProcess[0][1])

		check_update = run_query(query)
		fichero = '../uploads/'+toProcess[0][1]
		print fichero
		k = paramiko.RSAKey.from_private_key_file("your.pem")
		c = paramiko.SSHClient()
		c.set_missing_host_key_policy(paramiko.AutoAddPolicy())
		print "connecting"
		c.connect( hostname = "domain-amazon.com", username = "ec2-user", pkey = k )
	#print c
		print "connected"
		scp = SCPClient(c.get_transport())
	
		scp.put(fichero)
		scp.close()
		c.close()

		print check_update


                query = "UPDATE wifi_data SET process=3 WHERE file='%s'" % (toProcess[0][1])

                check_update = run_query(query)



	else:
		print "no hay nada que procesar"

else:
	print "Ya se esta procesando"
