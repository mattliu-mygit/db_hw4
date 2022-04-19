DROP PROCEDURE IF EXISTS HW4_CHECKPASSWORD;
CREATE PROCEDURE HW4_CHECKPASSWORD(IN inputPass VARCHAR(10))
SELECT *
FROM HW4_Password
WHERE CurPasswords = inputPass;
DROP PROCEDURE IF EXISTS HW4_GETASSIGNMENTS;
CREATE PROCEDURE HW4_GETASSIGNMENTS()
SELECT *
FROM HW4_Assignment;
DROP PROCEDURE IF EXISTS HW4_SHOWALLRAWSCORES;
CREATE PROCEDURE HW4_SHOWALLRAWSCORES() WITH PERCENTAGES AS (
  Select HW4_Student.SID AS SID,
    LName,
    FName,
    Sec,
    AName,
    Score
  FROM HW4_Student
    JOIN (
      SELECT HW4_RawScore.Score as Score,
        SID,
        HW4_RawScore.AName
      FROM HW4_RawScore
        JOIN HW4_Assignment ON HW4_RawScore.AName = HW4_Assignment.AName
    ) as J ON HW4_Student.SID = J.SID
),
NOTIN AS (
  SELECT *
  FROM HW4_Assignment
  WHERE NOT EXISTS(
      SELECT PERCENTAGES.AName
      FROM PERCENTAGES
      WHERE PERCENTAGES.AName = HW4_Assignment.AName
    )
),
NEWROWS AS (
  SELECT *,
    null AS Score
  FROM NOTIN
    JOIN HW4_Student
),
ALL_ASSESSMENTS AS (
  SELECT *
  FROM PERCENTAGES
  UNION
  SELECT SID,
    LName,
    FName,
    Sec,
    AName,
    Score
  FROM NEWROWS
)
SELECT *
FROM ALL_ASSESSMENTS
ORDER BY Sec,
  LName,
  FName,
  AName ASC;