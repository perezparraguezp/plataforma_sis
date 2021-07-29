#!/bin/bash

/sbin/iptables -F
#Bloqueamos https facebook y youtube

/sbin/iptables -I FORWARD -p tcp --dport 443 -m string --string 'facebook.com' --algo bm -j DROP
/sbin/iptables -I FORWARD -p tcp --dport 443 -m string --string 'youtube.com' --algo bm -j DROP


#Damos acceso a https a facebook y youtube a las siguienes IP

/sbin/iptables -I FORWARD -s 172.15.224.242 -p tcp --dport 443 -m string --string 'facebook.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.229.244 -p tcp --dport 443 -m string --string 'facebook.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.252.246 -p tcp --dport 443 -m string --string 'facebook.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.249.249 -p tcp --dport 443 -m string --string 'facebook.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.255.192 -p tcp --dport 443 -m string --string 'facebook.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.253.253 -p tcp --dport 443 -m string --string 'facebook.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.255.197 -p tcp --dport 443 -m string --string 'facebook.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.252.236 -p tcp --dport 443 -m string --string 'facebook.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.250.204 -p tcp --dport 443 -m string --string 'facebook.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.0.2 -p tcp --dport 443 -m string --string 'facebook.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.0.30 -p tcp --dport 443 -m string --string 'facebook.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.231.241 -p tcp --dport 443 -m string --string 'facebook.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.87.180 -p tcp --dport 443 -m string --string 'facebook.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.248.228 -p tcp --dport 443 -m string --string 'facebook.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.253.181 -p tcp --dport 443 -m string --string 'facebook.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.241.208 -p tcp --dport 443 -m string --string 'facebook.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.88.45 -p tcp --dport 443 -m string --string 'facebook.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.224.253 -p tcp --dport 443 -m string --string 'facebook.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.0.22 -p tcp --dport 443 -m string --string 'facebook.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.248.194 -p tcp --dport 443 -m string --string 'facebook.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.248.191 -p tcp --dport 443 -m string --string 'facebook.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.225.253 -p tcp --dport 443 -m string --string 'facebook.com' --algo bm -j ACCEPT 
/sbin/iptables -I FORWARD -s 172.15.252.253 -p tcp --dport 443 -m string --string 'facebook.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.10.19 -p tcp --dport 443 -m string --string 'facebook.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.110.150 -p tcp --dport 443 -m string --string 'facebook.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.87.199 -p tcp --dport 443 -m string --string 'facebook.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.87.179 -p tcp --dport 443 -m string --string 'facebook.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.10.15 -p tcp --dport 443 -m string --string 'facebook.com' --algo bm -j ACCEPT

/sbin/iptables -I FORWARD -s 172.15.224.242 -p tcp --dport 443 -m string --string 'youtube.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.229.244 -p tcp --dport 443 -m string --string 'youtube.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.252.246 -p tcp --dport 443 -m string --string 'youtube.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.249.249 -p tcp --dport 443 -m string --string 'youtube.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.255.192 -p tcp --dport 443 -m string --string 'youtube.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.253.253 -p tcp --dport 443 -m string --string 'youtube.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.255.197 -p tcp --dport 443 -m string --string 'youtube.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.252.236 -p tcp --dport 443 -m string --string 'youtube.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.250.204 -p tcp --dport 443 -m string --string 'youtube.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.0.2 -p tcp --dport 443 -m string --string 'youtube.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.0.30 -p tcp --dport 443 -m string --string 'youtube.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.87.180 -p tcp --dport 443 -m string --string 'youtube.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.248.228 -p tcp --dport 443 -m string --string 'youtube.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.253.181 -p tcp --dport 443 -m string --string 'youtube.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.241.208 -p tcp --dport 443 -m string --string 'youtube.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.0.22 -p tcp --dport 443 -m string --string 'youtube.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.87.158 -p tcp --dport 443 -m string --string 'youtube.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.248.191 -p tcp --dport 443 -m string --string 'youtube.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.110.150 -p tcp --dport 443 -m string --string 'youtube.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.87.199 -p tcp --dport 443 -m string --string 'youtube.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.87.179 -p tcp --dport 443 -m string --string 'youtube.com' --algo bm -j ACCEPT
/sbin/iptables -I FORWARD -s 172.15.10.15 -p tcp --dport 443 -m string --string 'youtube.com' --algo bm -j ACCEPT
