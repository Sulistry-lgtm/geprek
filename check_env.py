import paramiko

def main():
    ssh = paramiko.SSHClient()
    ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
    ssh.connect('172.16.63.152', username='sulis', password='1')
    
    stdin, stdout, stderr = ssh.exec_command('cat /var/www/geprek_bestie/.env | grep DB_')
    print(stdout.read().decode())
    ssh.close()

if __name__ == "__main__":
    main()
