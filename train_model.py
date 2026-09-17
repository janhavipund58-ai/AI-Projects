import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.ensemble import RandomForestClassifier
from sklearn.metrics import accuracy_score, classification_report
import joblib

# Load training data
df = pd.read_csv("resource_training_data.csv")

# Features
X = df[
    [
        "experience",
        "proficiency",
        "required_level"
    ]
]

# Target
y = df["match"]

# Split data
X_train, X_test, y_train, y_test = train_test_split(
    X,
    y,
    test_size=0.25,
    random_state=42,
    stratify=y
)

# Create Random Forest model
model = RandomForestClassifier(
    n_estimators=100,
    random_state=42
)

# Train model
model.fit(X_train, y_train)

# Test model
y_pred = model.predict(X_test)

# Accuracy
accuracy = accuracy_score(y_test, y_pred)

print("===== RANDOM FOREST MODEL =====")
print("Accuracy:", round(accuracy * 100, 2), "%")

print("\n===== CLASSIFICATION REPORT =====")
print(classification_report(y_test, y_pred, zero_division=0))

# Save model
joblib.dump(model, "resource_matching_model.pkl")

print("\nModel trained successfully!")
print("Model saved as: resource_matching_model.pkl")