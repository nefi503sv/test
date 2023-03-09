clear

if [ ! -d "bot" ]; then
	mkdir bot
fi

cd bot

if [ -e "dadosBot.ini" ] ; then

	screen -X -S bot quit > /dev/null
	screen -dmS bot php bot.php
	echo "El bot se ha reiniciado y se está ejecutando en segundo plano"

else

echo "Instalando dependencias, aguarde..."

#add-apt-repository ppa:ondrej/php > /dev/null 2>&1

apt-get update > /dev/null 2>&1
apt-get upgrade -y > /dev/null 2>&1
apt-get install php -y > /dev/null 2>&1
apt-get install php-redis -y > /dev/null 2>&1
apt-get install php-curl -y > /dev/null 2>&1
apt-get install php5 -y > /dev/null 2>&1
apt-get install php5-redis -y > /dev/null 2>&1
apt-get install php5-curl -y > /dev/null 2>&1
apt-get install redis-server -y > /dev/null 2>&1
apt-get install redis -y > /dev/null 2>&1
apt-get install screen -y > /dev/null 2>&1
apt-get install zip -y > /dev/null 2>&1

wget https://www.dropbox.com/s/hnqqqby0f358ilh/gerarusuario-sshplus.sh?dl=0 -O gerarusuario.sh; chmod +x gerarusuario.sh > /dev/null

wget https://www.dropbox.com/s/hnqqqby0f358ilh/gerarusuario-sshplus.sh?dl=0 -O gerarusuario.sh; chmod +x gerarusuario.sh > /dev/null

rm dadosBot.ini > /dev/null

clear

ip=$(wget -qO- ipv4.icanhazip.com/)

echo "Escriba el token del bot mi king:"
read token
clear
echo "ip=$ip
token=$token
limite=100" >> dadosBot.ini

screen -dmS bot php bot.php

echo "De acuerdo, el bot se está ejecutando en Segundo plano .
Mod, El Mandarin Sniff @gatesccn"

fi
