IT Project Resource Allocation System

📌 Project Overview

The IT Project Resource Allocation System is a DBMS-based web application developed for efficient management and allocation of IT resources to projects.

The system manages resources, skills, experience, project requirements, availability, and project allocation. It also includes an AI/ML-based resource-project matching concept to help identify suitable resources according to project requirements.

🎯 Objectives

- Manage IT resources efficiently.
- Maintain resource information in a MySQL database.
- Manage resource skills and experience.
- Manage IT projects and their requirements.
- Check resource availability.
- Allocate suitable resources to projects.
- Reduce manual resource allocation.
- Provide AI/ML-based resource matching.
- Generate resource and project reports.

🛠️ Technologies Used

Technology| Purpose
HTML| Web page structure
CSS| Website design
JavaScript| Client-side functionality
PHP| Backend development
MySQL| Database management
Python| Machine learning
XAMPP| Local development
phpMyAdmin| Database management
VS Code| Code development
InfinityFree| Web hosting
GitHub| Source-code management

🧩 Main Modules

🔐 Login

Provides access to the system through a login interface.

📊 Dashboard

Displays the main system modules and important resource/project information.

👨‍💻 Resource Management

Manages resource information including:

- Resource name
- Designation
- Experience
- Skills
- Availability

🏢 Project Management

Manages IT project information and project requirements.

📅 Resource Availability

Checks the availability status of resources before allocation.

🔄 Project Allocation

Allocates available resources to suitable projects.

🤖 AI/ML Resource Matching

The AI/ML component focuses on matching resources with project requirements based on factors such as:

- Skills
- Experience
- Availability
- Required project skills
- Skill-match percentage

📈 Reports

Provides useful information related to resources, projects, and allocations.

🚪 Logout

Allows the user to safely exit the system.

🗄️ Database

The application uses MySQL for storing and managing system data.

Major data areas include:

- Users
- Resources
- Skills
- Projects
- Allocations
- Availability

🧠 AI/ML Component

The project includes an AI/ML concept for resource-project matching.

The purpose of the matching component is to identify resources whose skills and experience correspond to the requirements of a project.

Example:

Project Required Skills
        ↓
Compare Resource Skills
        ↓
Check Experience
        ↓
Check Availability
        ↓
Calculate Skill Match
        ↓
Recommend Suitable Resource

📐 ER Diagram

The project includes an Entity Relationship (ER) Diagram showing the relationships between the major entities used in the database.

File:

ER diagram.png

💻 DBMS Concepts Implemented

The project demonstrates the following DBMS concepts:

- DDL
- DML
- DQL
- DCL
- TCL
- Primary Key
- Foreign Key
- Constraints
- Joins
- Aggregate Functions
- GROUP BY
- HAVING
- ORDER BY
- Normalization
- Stored Procedures
- Triggers
- Database Relationships
- ER Diagram

🔄 System Workflow

Login
   ↓
Dashboard
   ↓
Resource Management
   ↓
Project Management
   ↓
Resource Availability
   ↓
AI/ML Resource Matching
   ↓
Project Allocation
   ↓
Reports
   ↓
Logout

📂 Project Files

Important project files include:

index.php
db.php
login.php
dashboard.php
allocation.php
availability.php
logout.php
AI matching.php
ML dataset.py
training data.py
ER diagram.png
PROJECT_DOCUMENTATION.md

The exact files may vary according to the final project version.

🌐 Live Project

Live Website:

https://itresourceallocation.infinityfreeapp.com/

The web application is hosted on InfinityFree.

⚙️ Local Setup

Step 1 — Install XAMPP

Start:

Apache
MySQL

Step 2 — Place the Project

Copy the project folder into:

C:\xampp\htdocs\

Step 3 — Create the Database

Open:

http://localhost/phpmyadmin

Create the required database and tables.

Step 4 — Configure Database Connection

Update the database connection settings in:

db.php

Step 5 — Run the Project

Open the project through:

http://localhost/

and select/open the project folder.

🔒 Security

Do not upload real database passwords, API keys, or other private credentials to a public GitHub repository.

For deployment, sensitive database credentials should be stored separately from publicly accessible source code.

🚀 Future Scope

Possible future improvements include:

- Advanced AI-based resource recommendation
- Role-based authentication
- Email notifications
- Advanced analytics
- Resource workload prediction
- Project deadline prediction
- Cloud database integration
- Mobile-responsive improvements
- Stronger authentication and password hashing

👩‍💻 Project Information

Project Title:
IT Project Resource Allocation System

Domain:
IT Project Management

AI/DS Integration:
Resource-Project Matching

Backend:
PHP

Database:
MySQL

Machine Learning:
Python

Hosting:
InfinityFree

Repository:
GitHub

📜 Purpose

This project is developed as an academic mini project to demonstrate practical implementation of DBMS, web development, and AI/ML concepts in an IT project management scenario.

🌐 Live Project

Live Website:
https://itresourceallocation.great-site.net/

📜 Purpose

This project is developed as an academic mini project to demonstrate practical implementation of DBMS, web development, and AI/ML concepts in an IT project management scenario.
