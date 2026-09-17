import mysql.connector
import pandas as pd

# Connect to MySQL
conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="itresourcedb"
)

# Get available resources and their skills
query = """
SELECT
    r.resource_id,
    r.resource_name,
    s.skill_name,
    rs.proficiency,
    r.experience,
    r.availability_status
FROM resources r
JOIN resource_skills rs
    ON r.resource_id = rs.resource_id
JOIN skills s
    ON rs.skill_id = s.skill_id
WHERE r.availability_status = 'Available';
"""

df = pd.read_sql(query, conn)

# Project requirements
project_requirements = {
    "Python": 8,
    "SQL": 7,
    "Machine Learning": 9
}

# Calculate matching score
results = []

for resource_id in df["resource_id"].unique():

    resource = df[df["resource_id"] == resource_id]

    total_score = 0
    matched_skills = 0

    for skill, required_level in project_requirements.items():

        skill_data = resource[resource["skill_name"] == skill]

        if not skill_data.empty:

            proficiency = skill_data.iloc[0]["proficiency"]

            if proficiency >= required_level:
                total_score += 40
            else:
                total_score += (proficiency / required_level) * 40

            matched_skills += 1

    experience = resource.iloc[0]["experience"]

    # Experience score
    experience_score = min(experience * 4, 20)

    # Final score
    final_score = total_score + experience_score

    results.append({
        "Resource": resource.iloc[0]["resource_name"],
        "Matched Skills": matched_skills,
        "Experience": experience,
        "Matching Score": round(final_score, 2)
    })

# Convert results into DataFrame
result_df = pd.DataFrame(results)

# Sort by highest matching score
result_df = result_df.sort_values(
    by="Matching Score",
    ascending=False
)

print("\n===== RESOURCE MATCHING RESULT =====")
print(result_df.to_string(index=False))

# Best resource
best_resource = result_df.iloc[0]

print("\n===== RECOMMENDED RESOURCE =====")
print("Resource:", best_resource["Resource"])
print("Matching Score:", best_resource["Matching Score"])

conn.close()