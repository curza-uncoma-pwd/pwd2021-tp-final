git fetch upstream
git merge upstream/main

IF %ERRORLEVEL% EQU 0 (
  git push
)
