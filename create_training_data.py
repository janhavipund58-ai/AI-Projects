import mysql.connector
import pandas as pd

# Connect to MySQL
conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="itresourcedb"
)

# Get resource-project matching data
query = """
SELECT
    r.resource_id,
    r.resource_name,
    p.project_id,
    p.project_name,
    r.experience,
    rs.proficiency,
    pr.required_level,
    r.availability_status
FROM resources r
JOIN resource_skills rs
    ON r.resource_id = rs.resource_id
JOIN project_requirements pr
    ON rs.skill_id = pr.skill_id
JOIN projects p
    ON pr.project_id = p.project_id;
"""

df = pd.read_sql(query, conn)

# Create target variable
# 1 = Good Match
# 0 = Not a Good Match
df["match"] = (
    (df["proficiency"] >= df["required_level"]) &
    (df["availability_status"] == "Available")
).astype(int)

print("\n===== TRAINING DATA =====")
print(df)

print("\n===== MATCH DISTRIBUTION =====")
print(df["match"].value_counts())

# Save training data
df.to_csv("resource_training_data.csv", index=False)

print("\nTraining dataset created successfully!")
print("File: resource_training_data.csv")

conn.close()