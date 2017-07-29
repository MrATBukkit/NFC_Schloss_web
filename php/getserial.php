<?php
shell_exec('sudo service nfcschloss stop');
sleep(0.5);
print shell_exec("sudo python3 /home/pi/NFC-Schloss/nfctext.py");
sleep(0.5);
shell_exec('sudo service nfcschloss start');
?>