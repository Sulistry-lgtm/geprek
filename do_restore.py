import paramiko
from scp import SCPClient

def main():
    print("Connecting to Ubuntu server...")
    ssh = paramiko.SSHClient()
    ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    
    try:
        ssh.connect('172.16.63.152', username='sulis', password='1')
        print("Connected! Uploading database dump...")
        
        with SCPClient(ssh.get_transport()) as scp:
            scp.put('C:\\xampp\\htdocs\\geprek_bestie\\geprekbestie_dump.sql', '~/geprekbestie_dump.sql')
        
        print("Upload complete. Importing database...")
        command = "export DB_PASS=$(grep '^DB_PASSWORD=' /var/www/geprek_bestie/.env | sed 's/^DB_PASSWORD=//' | tr -d '\"'); mysql -u geprek_user -p\"$DB_PASS\" geprek_bestie < ~/geprekbestie_dump.sql"
        stdin, stdout, stderr = ssh.exec_command(command)
        
        out = stdout.read().decode(errors='ignore')
        err = stderr.read().decode(errors='ignore')
        
        if out:
            print(out)
        if err:
            print("Error/Warning:", err)
            
        print("Restore successfully completed!")
    except Exception as e:
        print("Failed:", str(e))
    finally:
        ssh.close()

if __name__ == "__main__":
    main()
