import sys
import mysql.connector
import pandas as pd
import joblib


# --------------------------------------------------
# 1. Get Project ID from PHP
# --------------------------------------------------

if len(sys.argv) < 2:
    print("No project selected")
    sys.exit()


project_id = int(sys.argv[1])


# --------------------------------------------------
# 2. Load trained ML model
# --------------------------------------------------

model = joblib.load("resource_matching_model.pkl")


# --------------------------------------------------
# 3. Connect to MySQL
# --------------------------------------------------

conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="itresourcedb"
)


# --------------------------------------------------
# 4. Get project requirements
# --------------------------------------------------

query = """
SELECT
    p.project_id,
    p.project_name,
    pr.skill_id,
    s.skill_name,
    pr.required_level
FROM projects p
JOIN project_requirements pr
    ON p.project_id = pr.project_id
JOIN skills s
    ON pr.skill_id = s.skill_id
WHERE p.project_id = %s
"""

requirements = pd.read_sql(
    query,
    conn,
    params=(project_id,)
)


if requirements.empty:
    print("No project requirements found")
    conn.close()
    sys.exit()


# --------------------------------------------------
# 5. Get available resources and their skills
# --------------------------------------------------

resource_query = """
SELECT
    r.resource_id,
    r.resource_name,
    r.designation,
    r.experience,
    r.availability_status,
    s.skill_id,
    s.skill_name,
    rs.proficiency
FROM resources r
JOIN resource_skills rs
    ON r.resource_id = rs.resource_id
JOIN skills s
    ON rs.skill_id = s.skill_id
WHERE r.availability_status = 'Available'
"""

resources = pd.read_sql(resource_query, conn)


if resources.empty:
    print("No available resources found")
    conn.close()
    sys.exit()


# --------------------------------------------------
# 6. Find matching resources
# --------------------------------------------------

results = []


for resource_id in resources["resource_id"].unique():

    resource = resources[
        resources["resource_id"] == resource_id
    ]

    total_prediction = 0
    matched_skills = 0

    for _, requirement in requirements.iterrows():

        skill_id = requirement["skill_id"]
        required_level = requirement["required_level"]

        skill_data = resource[
            resource["skill_id"] == skill_id
        ]

        if not skill_data.empty:

            proficiency = skill_data.iloc[0]["proficiency"]

            # ML prediction
            prediction = model.predict([
                [
                    resource.iloc[0]["experience"],
                    proficiency,
                    required_level
                ]
            ])[0]

            total_prediction += prediction

            if prediction == 1:
                matched_skills += 1


    # --------------------------------------------------
    # 7. Calculate matching percentage
    # --------------------------------------------------

    total_skills = len(requirements)

    if total_skills > 0:
        match_percentage = (
            matched_skills / total_skills
        ) * 100
    else:
        match_percentage = 0


    results.append({
        "resource_id":
            resource.iloc[0]["resource_id"],

        "resource_name":
            resource.iloc[0]["resource_name"],

        "designation":
            resource.iloc[0]["designation"],

        "experience":
            resource.iloc[0]["experience"],

        "matched_skills":
            matched_skills,

        "total_skills":
            total_skills,

        "match_percentage":
            round(match_percentage, 2)
    })


# --------------------------------------------------
# 8. Convert results into DataFrame
# --------------------------------------------------

result_df = pd.DataFrame(results)


# --------------------------------------------------
# 9. Select best resource
# --------------------------------------------------

if result_df.empty:

    print("No suitable resource found")

else:

    result_df = result_df.sort_values(
        by=[
            "match_percentage",
            "experience"
        ],
        ascending=False
    )

    best = result_df.iloc[0]


    # --------------------------------------------------
    # 10. Display recommendation
    # --------------------------------------------------

    print("===== AI RESOURCE RECOMMENDATION =====")

    print(
        "Resource:",
        best["resource_name"]
    )

    print(
        "Designation:",
        best["designation"]
    )

    print(
        "Experience:",
        best["experience"],
        "Years"
    )

    print(
        "Matched Skills:",
        int(best["matched_skills"]),
        "/",
        int(best["total_skills"])
    )

    print(
        "Match Percentage:",
        best["match_percentage"],
        "%"
    )


conn.close()