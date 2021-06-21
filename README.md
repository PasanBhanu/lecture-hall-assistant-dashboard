# Lecture Hall Assistant - Control Dashboard

![alt text](https://i.ibb.co/ZV05pqJ/IEEE-YESIST12-Logo-1024x307.png "IEEESS12 Logo")

## Introduction

SS12 is an innovation challenge organized to recognize the best social innovators globally. The theme for this year is 'EMPATHY TO ENGINEERING: SOLUTIONS FOR MACRO ISSUES'. Students form global Universities will be participating displaying their engineering skills. 

Team Technocrats from University of Moratuwa participated IEEE SS12 2018 International Competition held in NSBM Green University Town, Sri Lanka. Team Technocrats won the 1st Runner Up in Maker Fair competition. The winning team members are,

+ Vinura Udaraka
+ Pasan Bhanu Guruge
+ Chathura Dilshan
+ Hiranya Panawenna

This project is to overcome the common audio issues in lecture halls in developing countries. This system detects audio levels of the speaker and background and control the amplifier audio level automatically and continuously throughout the lecture. Not only that but also this system has a local file server to share lecture materials with students when there is no internet connection to the lecture hall and control lights and fans via their mobile phones. 

![alt text](https://i.ibb.co/Czgfc9Z/image.png "IEEESS12 2018 Results")

## Main Repositories

1. [API](https://github.com/PasanBhanu/iot-api-lecture-hall-assistant)
2. [Control Dashboard](https://github.com/PasanBhanu/lecture-hall-assistant-dashboard)
3. [NodeMCU Codes](#)

### Repository Introduction

This system is a web application developed using PHP, HTML, CSS and JS. This communicate with the database and perform control functions.

#### API
API used to communicate with the Arduino Devices via HTTP protocol. All the switches, sensors and controllers communicate with central system using the API.

#### Control Dashboard
This is the main web interface for the users of the system. This has 2 separate access parts for students and lecturers. All the functions can be controlled via the dashboard.

#### Arduino System
This NodeMCU modules are the main hardware part in this project. These devices communicate with API and control the functions and send sensor data to the system. There are 2 main type of devices.

1. Light Controllers (Remote Control Switches)
2. Audio Sensors

A local area network used to build the connectivity between all devices.

## Implementation

This system can be easily implemented in any university lecture hall. Please contact us for more information.

Email : pasanbguruge@gmail.com / vinurawa@gmail.com
