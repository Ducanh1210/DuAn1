# Comparison Report: movies.sql vs duan.sql

## Overview
- **movies.sql**: Generated Nov 20, 2025 at 03:14 PM (1901 lines)
- **duan.sql**: Generated Nov 22, 2025 at 02:56 PM (1865 lines) - **NEW from project leader**

## Key Differences

### 1. Database Name
- **movies.sql**: `du_an_1`
- **duan.sql**: `duan` ⚠️ **DIFFERENT**

### 2. showtimes Table Structure
- **movies.sql**: Has `AUTO_INCREMENT` on `id` column (line 1447)
  ```sql
  `id` int NOT NULL AUTO_INCREMENT,
  ```
- **duan.sql**: Does NOT have `AUTO_INCREMENT` (line 1446)
  ```sql
  `id` int NOT NULL,
  ```
  ⚠️ **CRITICAL**: This will cause issues when inserting new showtimes!

### 3. ticket_prices Table Data
- **movies.sql**: Uses `INSERT ... ON DUPLICATE KEY UPDATE` (lines 1502-1526)
  - More flexible, updates existing records
  - No actual data rows shown
  
- **duan.sql**: Has actual INSERT statements with data (lines 1493-1509)
  - Contains 16 price records with IDs 1-16
  - Has `created_at` and `updated_at` timestamps
  - AUTO_INCREMENT set to 17 (line 1781)

### 4. showtimes Format Update Script
- **movies.sql**: Contains update script at the end (lines 1883-1894)
  ```sql
  UPDATE showtimes s
  INNER JOIN movies m ON s.movie_id = m.id
  SET s.format = ...
  ```
- **duan.sql**: ❌ **MISSING** - No format update script

### 5. discount_codes Table Comment
- **movies.sql**: Has comment about food discounts (line 141)
  ```sql
  -- Note: All discount codes apply to tickets only. Food discounts are not supported.
  ```
- **duan.sql**: ❌ **MISSING** - No comment

### 6. showtimes Data Differences
Some showtimes have different format values:

| ID | movies.sql format | duan.sql format | Movie ID |
|----|-------------------|-----------------|----------|
| 2  | '2D'              | '3D'            | 7        |
| 5  | '2D'              | '3D'            | 6        |
| 8  | '2D'              | '3D'            | 7        |

### 7. AUTO_INCREMENT Values
- **movies.sql**: 
  - `ticket_prices`: AUTO_INCREMENT=1 (line 1800)
  - `discount_codes`: AUTO_INCREMENT not specified (defaults to 1)
  
- **duan.sql**:
  - `ticket_prices`: AUTO_INCREMENT=17 (line 1781) ✅ Has data
  - `discount_codes`: AUTO_INCREMENT=6 (line 1715) ✅ Has data

## Files That Were Merged into movies.sql

Based on the separate SQL files found:

1. **fix_showtimes_format.sql** ✅ - Already merged into movies.sql (lines 1883-1894)
2. **fix_showtimes_auto_increment.sql** ✅ - Already applied in movies.sql (AUTO_INCREMENT present)
3. **ticket_prices.sql** ⚠️ - Partially merged (structure + INSERT logic, but no actual data)
4. **add_user_status.sql** ✅ - Already applied (status column exists in both)

## Recommendations

### ✅ KEEP from movies.sql:
1. **showtimes AUTO_INCREMENT** - Critical for functionality
2. **showtimes format update script** - Ensures data consistency
3. **discount_codes comment** - Important documentation
4. **ticket_prices INSERT with ON DUPLICATE KEY UPDATE** - More flexible approach

### ✅ KEEP from duan.sql:
1. **ticket_prices actual data** - The 16 price records are needed
2. **Database name `duan`** - If this is the official name

### ⚠️ ACTION REQUIRED:
1. **Add AUTO_INCREMENT to showtimes.id in duan.sql**
2. **Add showtimes format update script to duan.sql**
3. **Add discount_codes comment to duan.sql**
4. **Merge ticket_prices approach**: Use INSERT with ON DUPLICATE KEY UPDATE but include the actual data
5. **Fix showtimes format values** - Some are inconsistent (IDs 2, 5, 8)

## Suggested Merge Strategy

1. Use **duan.sql** as base (newer, from project leader)
2. Add AUTO_INCREMENT to showtimes table
3. Add the format update script at the end
4. Add the discount_codes comment
5. Keep ticket_prices data but use ON DUPLICATE KEY UPDATE approach
6. Fix inconsistent showtimes format values

