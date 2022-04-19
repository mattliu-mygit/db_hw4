Select HW4_Student.SID,
  Lname,
  FName,
  Sec,
  AName,
  Score
FROM HW4_Student
  JOIN HW4_RawScore ON HW4_Student.SID = HW4_RawScore.SID
WHERE HW4_Student.SID = 1006;