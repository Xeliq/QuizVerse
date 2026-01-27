## Preparing environment
```bash
python3 -m venv .venv
# linux
source .venv/bin/activate 
# windows
.\.venv\Scripts\Activate.ps1
pip install robotframework robotframework-requests 
```
## register new user for testing if it does not exist already
- email: `test@test.test`
- password: `testtest`
## Run tests (from quizverse directory)
```bash
robot -d results tests
```
