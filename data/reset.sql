DELETE FROM data; 
DELETE FROM availability;
UPDATE buildings SET scale = 1, wealth = 0;
UPDATE trains SET Car_1 = NULL, Car_2 = NULL, Car_3 = NULL, Car_4 = NULL, Car_5 = NULL, Car_6 = NULL, Car_7 = NULL, Car_8 = NULL;