DROP PROCEDURE IF EXISTS HW4_SHOWPERCENTAGES;
CREATE PROCEDURE HW4_SHOWPERCENTAGES(IN InputSID VARCHAR(4)) WITH inputS AS (
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
    Score,
    PtsPoss,
    Atype
  FROM inputS
    JOIN (
      SELECT HW4_RawScore.Score as Score,
        PtsPoss,
        AType,
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
    0 AS Score
  FROM NOTIN
    JOIN inputS
),
ALL_ASSESSMENTS AS (
  SELECT *,
    ROUND((Score / PtsPoss) * 100, 2) as pctg
  FROM PERCENTAGES
  UNION
  SELECT SID,
    LName,
    FName,
    Sec,
    AName,
    Score,
    PtsPoss,
    Atype,
    ROUND((Score / PtsPoss) * 100, 2) as pctg
  FROM NEWROWS
),
AVG_ASSESSMENTS AS (
  SELECT AType,
    AVG(Score / PtsPoss) AS Average
  FROM ALL_ASSESSMENTS
  GROUP BY Atype
),
W_EXAM AS (
  SELECT 0.6 * Average AS W_AVG
  FROM AVG_ASSESSMENTS
  WHERE AType = 'EXAM'
),
W_QUIZ AS (
  SELECT 0.4 * Average AS W_AVG
  FROM AVG_ASSESSMENTS
  WHERE AType = 'QUIZ'
),
COURSE_AVG AS (
  SELECT ROUND((W_EXAM.W_AVG + W_QUIZ.W_AVG) * 100, 2) AS Course_Average
  FROM W_EXAM
    JOIN W_QUIZ
)
SELECT SID,
  LName,
  FName,
  Sec,
  AName,
  pctg,
  Course_Average
FROM ALL_ASSESSMENTS
  JOIN COURSE_AVG;