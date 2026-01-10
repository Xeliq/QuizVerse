## Preparing environment
```bash
python3 -m venv .venv
source .venv/bin/activate
pip install robotframework robotframework-requests
```
## register new user for testing if it does not exist already
- email: `test@test.test`
- password: `testtest`
## Run tests (from quizverse directory)
```bash
robot -d results tests
```
