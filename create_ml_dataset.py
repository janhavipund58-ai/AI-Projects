import mysql.connector
import pandas as pd

# Connect to MySQL database
conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="itresourcedb"
)

# SQL query to collect ML data
query = """
SELECT
    r.resource_id,
    r.resource_name,
    r.designation,
    r.experience,
    r.availability_status,
    s.skill_name,
    rs.proficiency
FROM resources r
JOIN resource_skills rs
    ON r.resource_id = rs.resource_id
JOIN skills s
    ON rs.skill_id = s.skill_id;
"""

# Read database data into Pandas
df = pd.read_sql(query, conn)

# Display dataset
print("\n===== ML DATASET =====")
print(df)

print("\n===== DATASET INFORMATION =====")
print(df.info())

# Save dataset as CSV
df.to_csv("resource_ml_dataset.csv", index=False)

print("\nML dataset created successfully!")
print("File saved as: resource_ml_dataset.csv")

conn.close()