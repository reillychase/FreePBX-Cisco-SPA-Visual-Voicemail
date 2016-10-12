# FreePBX-Cisco-SPA-Visual-Voicemail
A Visual Voicemail application for Cisco SPA phones connected to an Asterisk/FreePBX phone server

## Intro
This was originally written by Alberto Montilla (https://supportforums.cisco.com/document/67156/visual-voicemailzip) and updated by Adam Goodfriend (https://supportforums.cisco.com/document/75261/visual-voicemail-updated-and-english).

I created this repo to continue to update and maintain this useful XML phone service. I plan to further translate to English and review code security.

https://supportforums.cisco.com/document/75261/visual-voicemail-updated-and-english

## Install Instructions
1. Create a directory named "voicemail" in "/var/www/html" or your web root
2. Add the "voicemail.php" script in this repo to that directory
3. Edit voicemail.php and change "$URLXML="http://www.example.com/cisco"" to your web server/FreePBX IP address
4. Add this line key code to your Cisco SPA phone, and replace "10.1.1.100" with your web server/FreePBX, "123" with your phone extension for Visual Voicemail:

```
<!-- Line Key 2 -->
<Extension_2_ ua="na">Disabled</Extension_2_> <!-- options: 1/2/3/4/5/6/7/8/9/10/11/12/Disabled -->
<Short_Name_2_ ua="na">Voicemail</Short_Name_2_>
<Share_Call_Appearance_2_ ua="na">private</Share_Call_Appearance_2_> <!-- options: private/shared -->
<Extended_Function_2_ ua="na">fnc=xml;url=http://10.1.1.100/voicemail/voicemail.php?user=123</Extended_Function_2_>
```


