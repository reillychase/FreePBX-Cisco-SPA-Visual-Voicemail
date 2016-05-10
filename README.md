# FreePBX-Cisco-SPA-Visual-Voicemail
A Visual Voicemail application for Cisco SPA phones connected to an Asterisk/FreePBX phone server

## Intro
This is a very useful application that enables us to have Visual Voicemail feature for Cisco SPA phones connected to FreePBX.

I am not the original author, I was just lucky enough to find it in a forum somewhere on the internet and have been using it for a few years now. The code and most of the comments are in Spanish. Unfortunately, I could not find the original author to credit them.

At this time it looks like the only remaining source on the internet that I could find is:
http://www.dslreports.com/forum/r27047614-Cisco-SPA525G-Visual-Voicemail-Asterisk-XML-script

So I created this repo for others to be able to find the code, and to improve it also.

## Install Instructions
1. Create a directory named "voicemail" in "/var/www/html" or your web root
2. Add the "voicemail.php" script in this repo to that directory
3. Edit voicemail.php and change "$URLXML="http://10.1.1.100/voicemail";" to your web server/FreePBX IP address
4. Add this line key code to your Cisco SPA phone, and replace "10.1.1.100" with your web server/FreePBX, "123" with your phone extension for Visual Voicemail:

```
<!-- Line Key 2 -->
<Extension_2_ ua="na">Disabled</Extension_2_> <!-- options: 1/2/3/4/5/6/7/8/9/10/11/12/Disabled -->
<Short_Name_2_ ua="na">Voicemail</Short_Name_2_>
<Share_Call_Appearance_2_ ua="na">private</Share_Call_Appearance_2_> <!-- options: private/shared -->
<Extended_Function_2_ ua="na">fnc=xml;url=http://10.1.1.100/voicemail/voicemail.php?user=123</Extended_Function_2_>
```


