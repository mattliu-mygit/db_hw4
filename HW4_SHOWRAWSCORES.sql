DROP PROCEDURE IF EXISTS HW4_SHOWRAWSCORES;
CREATE PROCEDURE HW4_SHOWRAWSCORES(IN InputSID VARCHAR(4)) WITH inputS AS (
  SELECT *
  FROM HW4_Student
  WHERE HW4_Student.SID = InputSID
),
PERCENTAGES AS (
  Select inputS.SID AS SID,
    LName,
    FName,
    Sec,
    AName,
    Score
  FROM inputS
    JOIN (
      SELECT HW4_RawScore.Score as Score,
        SID,
        HW4_RawScore.AName
      FROM HW4_RawScore
        JOIN HW4_Assignment ON HW4_RawScore.AName = HW4_Assignment.AName
    ) as J ON inputS.SID = J.SID
  WHERE inputS.SID = InputSID
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
    JOIN inputS
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
FROM ALL_ASSESSMENTS;