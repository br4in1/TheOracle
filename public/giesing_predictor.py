# -*- coding: utf-8 -*-
"""
@author: simo
"""

import MySQLdb
import pandas as pd
from catboost import CatBoostRegressor

# Open database connection
db = MySQLdb.connect(host='127.0.0.1', database='pi_ds', user='root', password='root',port = 3307)

# prepare a cursor object using cursor() method
cursor = db.cursor()

sql = "SELECT * FROM history WHERE canteen = '%s'" % ("Giesing")
try:
   # Execute the SQL command
   cursor.execute(sql)
   # Fetch all the rows in a list of lists.
   results = cursor.fetchall()

except:
    print("Error: unable to fecth data")

# disconnect from server
db.close()

history= pd.DataFrame(list(results))
history.columns=["id","Date","number","canteen"]
history.drop(["canteen"],axis=1,inplace= True)

all_dates = pd.date_range("01-01-2019","12-31-2020",freq = "D")
test= pd.DataFrame(all_dates)
test.columns=["Date"]
X_test= test

history['Date'] = pd.to_datetime(history['Date'])   
history['year'] = history.Date.dt.year
history['month'] = history.Date.dt.month
history['day'] = history.Date.dt.day

history["day_of_week"]=history["Date"].dt.dayofweek
history["week_of_year"]=history["Date"].dt.weekofyear
history["quarter"]=history["Date"].dt.quarter

history.drop('Date', axis=1, inplace=True)

history["year_Count"] = history["year"].map(history["year"].value_counts())
history["day_Count"] = history["day"].map(history["day"].value_counts())
history["month_Count"] = history["month"].map(history["month"].value_counts())
history["quarter_Count"] = history["quarter"].map(history["quarter"].value_counts())
history["day_of_week_Count"] = history["day_of_week"].map(history["day_of_week"].value_counts())
history["week_of_year_Count"] = history["week_of_year"].map(history["week_of_year"].value_counts())

X_test['Date'] = pd.to_datetime(X_test['Date'])   
X_test['year'] = X_test.Date.dt.year
X_test['month'] = X_test.Date.dt.month
X_test['day'] = X_test.Date.dt.day

X_test["day_of_week"]=X_test["Date"].dt.dayofweek
X_test["week_of_year"]=X_test["Date"].dt.weekofyear
X_test["quarter"]=X_test["Date"].dt.quarter

X_test.drop('Date', axis=1, inplace=True)

X_test["year_Count"] = X_test["year"].map(X_test["year"].value_counts())
X_test["day_Count"] = X_test["day"].map(X_test["day"].value_counts())
X_test["month_Count"] = X_test["month"].map(X_test["month"].value_counts())
X_test["quarter_Count"] = X_test["quarter"].map(X_test["quarter"].value_counts())
X_test["day_of_week_Count"] = X_test["day_of_week"].map(X_test["day_of_week"].value_counts())
X_test["week_of_year_Count"] = X_test["week_of_year"].map(X_test["week_of_year"].value_counts())

X_train= history.iloc[:,2:]
y_train= history.iloc[:,1]

model = CatBoostRegressor(
    learning_rate=0.05, 
    depth=10,  
    iterations = 2000,
    verbose = True,
    eval_metric='RMSE',
    task_type = 'CPU')

fit_model = model.fit(X_train, y_train)
pred = fit_model.predict(X_test)

all_dates = pd.date_range("01-01-2019","12-31-2020",freq = "D")
test= pd.DataFrame(all_dates)
test.columns=["Date"]
test["prediction"]=pred.astype(int)
test["canteen"]="Giesing"

# Open database connection
db = MySQLdb.connect(host='127.0.0.1', database='pi_ds', user='root', password='root',port = 3307)

# prepare a cursor object using cursor() method
cursor = db.cursor()

# Prepare SQL query to INSERT a record into the database.
sql = """INSERT INTO predictions(Date,
         number, canteen)
         VALUES (%s, %s,%s)"""

subset = test[['Date', 'prediction', 'canteen']]
records = [tuple(x) for x in subset.values]

try:
    cursor.executemany(sql,records)
    db.commit()
   # Commit your changes in the database
  
except:
   # Rollback in case there is any error
   db.rollback()

# disconnect from server
db.close()

