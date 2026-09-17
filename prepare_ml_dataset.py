import pandas as pd

# Load ML dataset
df = pd.read_csv("resource_ml_dataset.csv")

print("===== ORIGINAL DATASET =====")
print(df)

# Convert availability into numerical value
df["availability"] = df["availability_status"].apply(
    lambda x: 1 if x == "Available" else 0
)

# Rename proficiency for ML
df["skill_proficiency"] = df["proficiency"]

# Select ML features
features = df[
    ["experience", "skill_proficiency", "availability"]
]

print("\n===== PREPARED ML FEATURES =====")
print(features)

# Save prepared data
features.to_csv(
    "prepared_ml_dataset.csv",
    index=False
)

print("\nPrepared ML dataset created successfully!")
print("File saved as: prepared_ml_dataset.csv")