import pandas as pd

# Load the dataset
df = pd.read_csv("resource_ml_dataset.csv")

print("===== ORIGINAL DATASET =====")
print(df)

# Convert availability into numerical value
df["availability"] = df["availability_status"].apply(
    lambda x: 1 if x == "Available" else 0
)

# Create numerical skill feature
df["skill_proficiency"] = df["proficiency"]

# Select ML features
features = df[
    [
        "experience",
        "skill_proficiency",
        "availability"
    ]
]

print("\n===== ML FEATURES =====")
print(features)

# Save prepared dataset
features.to_csv("prepared_ml_dataset.csv", index=False)

print("\nPrepared ML dataset created successfully!")