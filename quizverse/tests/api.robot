*** Settings ***
Library    RequestsLibrary
Library    Collections
Library    json

*** Variables ***
${BASE_URL}    http://localhost:8000
${EMAIL}       test@test.test
${PASSWORD}    testtest

*** Test Cases ***
Login And Print Token
    Create Session    api    ${BASE_URL}
    &{payload}=    Create Dictionary    email=${EMAIL}    password=${PASSWORD}
    ${resp}=    POST On Session    api    /api/login    json=${payload}
    Should Be Equal As Integers    ${resp.status_code}    200
    ${data}=    Evaluate    json.loads('''${resp.text}''')    json
    ${has_token}=     Run Keyword And Return Status    Dictionary Should Contain Key    ${data}    token
    ${has_access}=    Run Keyword And Return Status    Dictionary Should Contain Key    ${data}    access_token
    ${token}=    Set Variable If    ${has_token}     ${data["token"]}    ${data["access_token"]}
    Set Suite Variable    ${token}
    Log To Console    \nTOKEN SAVED: ${token}


Get All Quizzes
    &{headers}=    Create Dictionary
    ...    accept=application/json
    ...    Authorization=Bearer ${token}
    ${resp}=    GET On Session    api    /api/all/quizzes    headers=${headers}
    Should Be Equal As Integers    ${resp.status_code}    200
    ${data}=    Evaluate    json.loads('''${resp.text}''')    json
    Log To Console    \n
    FOR    ${quiz}    IN    @{data}
        Log To Console    ${quiz["title"]}
    END

Create New Quiz
    &{headers}=    Create Dictionary
    ...    accept=application/json
    ...    Content-Type=application/json
    ...    Authorization=Bearer ${token}
    &{a1}=    Create Dictionary    text=966    is_correct=${True}
    @{answers}=    Create List    ${a1}
    &{q1}=    Create Dictionary
    ...    text=Kiedy został założony polski staat?
    ...    points=10
    ...    answers=${answers}
    @{questions}=    Create List    ${q1}
    &{payload}=    Create Dictionary
    ...    title=Historia Polski
    ...    description=string
    ...    category_id=1
    ...    questions=${questions}
    ${resp}=    POST On Session    api    /api/quizzes    headers=${headers}    json=${payload}
    Should Be True    ${resp.status_code} == 200 or ${resp.status_code} == 201
    ${data}=    Evaluate    json.loads('''${resp.text}''')    json
    Log To Console    RESPONSE: ${data}
    ${quiz_id}=    Set Variable    ${data["quiz"]["id"]}
    Set Suite Variable    ${quiz_id}
    Log To Console    \nCREATED QUIZ ID: ${quiz_id}

Verify Created Quiz Exists
    &{headers}=    Create Dictionary
    ...    accept=application/json
    ...    Authorization=Bearer ${token}
    ${resp}=    GET On Session    api    /api/quizzes/${quiz_id}    headers=${headers}
    Should Be Equal As Integers    ${resp.status_code}    200
    ${data}=    Evaluate    json.loads('''${resp.text}''')    json
    ${has_quiz}=    Run Keyword And Return Status    Dictionary Should Contain Key    ${data}    quiz
    ${quiz}=        Set Variable If    ${has_quiz}    ${data["quiz"]}    ${data}
    Should Be Equal As Integers    ${quiz["id"]}    ${quiz_id}
    Should Be Equal    ${quiz["title"]}    Historia Polski
    Log To Console    \nQUIZ VERIFIED: ${quiz["id"]} - ${quiz["title"]}

Get My Quizzes
    &{headers}=    Create Dictionary
    ...    accept=application/json
    ...    Authorization=Bearer ${token}
    ${resp}=    GET On Session    api    /api/quizzes    headers=${headers}
    Should Be Equal As Integers    ${resp.status_code}    200
    ${data}=    Evaluate    json.loads('''${resp.text}''')    json
    Log To Console    \n
    FOR    ${q}    IN    @{data}
        Log To Console    ${q["id"]} - ${q["title"]}
    END
    ${ids}=    Create List
    FOR    ${q}    IN    @{data}
        Append To List    ${ids}    ${q["id"]}
    END
    List Should Contain Value    ${ids}    ${quiz_id}

Get Categories
    &{headers}=    Create Dictionary
    ...    accept=application/json
    ...    Authorization=Bearer ${token}
    ${resp}=    GET On Session    api    /api/categories    headers=${headers}
    Should Be Equal As Integers    ${resp.status_code}    200
    ${data}=    Evaluate    json.loads('''${resp.text}''')    json
    Should Be Equal    ${data["status"]}    success
    Dictionary Should Contain Key    ${data}    categories
    Log To Console    \n
    FOR    ${cat}    IN    @{data["categories"]}
        Log To Console    ${cat["id"]} - ${cat["name"]}
    END

Create Quiz Comment
    &{headers}=    Create Dictionary
    ...    accept=application/json
    ...    Content-Type=application/json
    ...    Authorization=Bearer ${token}
    &{payload}=    Create Dictionary
    ...    content=Świetny quiz, polecam!
    ...    rating=5
    ${resp}=    POST On Session
    ...    api
    ...    /api/quizzes/${quiz_id}/comments
    ...    headers=${headers}
    ...    json=${payload}
    Should Be True    ${resp.status_code} == 200 or ${resp.status_code} == 201
    ${data}=    Evaluate    json.loads('''${resp.text}''')    json
    Log To Console    COMMENT CREATED: ${data}
    Dictionary Should Contain Key    ${data}    id
    Should Be Equal    ${data["content"]}    Świetny quiz, polecam!
    Should Be Equal As Integers    ${data["rating"]}    5
    ${comment_id}=    Set Variable    ${data["id"]}
    Set Suite Variable    ${comment_id}
    Log To Console    \nCOMMENT ID SAVED: ${comment_id}

Get Quiz Comments
    &{headers}=    Create Dictionary
    ...    accept=application/json
    ...    Authorization=Bearer ${token}
    ${resp}=    GET On Session    api    /api/quizzes/${quiz_id}/comments    headers=${headers}
    Should Be Equal As Integers    ${resp.status_code}    200
    ${comments}=    Evaluate    json.loads('''${resp.text}''')    json
    FOR    ${c}    IN    @{comments}
        Log To Console    ${c["id"]} | ${c["user_name"]} | ${c["rating"]} | ${c["content"]}
    END
    ${count}=    Get Length    ${comments}
    Should Be True    ${count} > 0

Access Protected Endpoint Without Token
    Create Session    api_noauth    ${BASE_URL}
    &{headers}=    Create Dictionary    accept=application/json
    Log To Console    \n[REQUEST] GET ${BASE_URL}/api/quizzes
    Log To Console    [HEADERS] ${headers}
    ${status}    ${out}=    Run Keyword And Ignore Error    GET On Session    api_noauth    /api/quizzes    headers=${headers}
    Log To Console    [RESULT] ${status}
    Run Keyword If    '${status}' == 'PASS'    Log To Console    [RESPONSE] ${out.status_code} | ${out.text}
    Run Keyword If    '${status}' == 'FAIL'    Log To Console    [ERROR] ${out}
    Should Be Equal    ${status}    FAIL
    Should Match Regexp    ${out}    .*\\b(401|403)\\b.*

Get Nonexistent Quiz Returns 404
    &{headers}=    Create Dictionary    accept=application/json    Authorization=Bearer ${token}
    Log To Console    \n[REQUEST] GET ${BASE_URL}/api/quizzes/99999999
    Log To Console    [HEADERS] ${headers}
    ${status}    ${out}=    Run Keyword And Ignore Error    GET On Session    api    /api/quizzes/99999999    headers=${headers}
    Log To Console    [RESULT] ${status}
    Run Keyword If    '${status}' == 'PASS'    Log To Console    [RESPONSE] ${out.status_code} | ${out.text}
    Run Keyword If    '${status}' == 'FAIL'    Log To Console    [ERROR] ${out}
    Should Be Equal    ${status}    FAIL
    Should Match Regexp    ${out}    .*\\b404\\b.*

Create Quiz Validation Returns 422
    &{headers}=    Create Dictionary
    ...    accept=application/json
    ...    Content-Type=application/json
    ...    Authorization=Bearer ${token}
    &{payload}=    Create Dictionary
    ...    title=
    ...    description=string
    ...    category_id=1
    ...    questions=@{EMPTY}
    Log To Console    \n[REQUEST] POST ${BASE_URL}/api/quizzes
    Log To Console    [HEADERS] ${headers}
    Log To Console    [PAYLOAD] ${payload}
    ${status}    ${out}=    Run Keyword And Ignore Error    POST On Session    api    /api/quizzes    headers=${headers}    json=${payload}
    Log To Console    [RESULT] ${status}
    Run Keyword If    '${status}' == 'PASS'    Log To Console    [RESPONSE] ${out.status_code} | ${out.text}
    Run Keyword If    '${status}' == 'FAIL'    Log To Console    [ERROR] ${out}
    Should Be Equal    ${status}    FAIL
    Should Match Regexp    ${out}    .*\\b422\\b.*

Is Correct Nonexistent Answer Returns 404
    &{headers}=    Create Dictionary    accept=application/json    Authorization=Bearer ${token}
    Log To Console    \n[REQUEST] POST ${BASE_URL}/api/questions/is-correct/99999999
    Log To Console    [HEADERS] ${headers}
    ${status}    ${out}=    Run Keyword And Ignore Error    POST On Session    api    /api/questions/is-correct/99999999    headers=${headers}
    Log To Console    [RESULT] ${status}
    Run Keyword If    '${status}' == 'PASS'    Log To Console    [RESPONSE] ${out.status_code} | ${out.text}
    Run Keyword If    '${status}' == 'FAIL'    Log To Console    [ERROR] ${out}
    Should Be Equal    ${status}    FAIL
    Should Match Regexp    ${out}    .*\\b404\\b.*

Add Question To Created Quiz
    &{headers}=    Create Dictionary
    ...    accept=application/json
    ...    Content-Type=application/json
    ...    Authorization=Bearer ${token}
    &{payload}=    Create Dictionary
    ...    text=Jaka jest stolica Polski?
    ...    points=10
    ${resp}=    POST On Session    api    /api/quizzes/${quiz_id}/questions    headers=${headers}    json=${payload}
    Should Be Equal As Integers    ${resp.status_code}    201
    ${data}=    Evaluate    json.loads('''${resp.text}''')    json
    Dictionary Should Contain Key    ${data}    question
    ${question_id}=    Set Variable    ${data["question"]["id"]}
    Set Suite Variable    ${question_id}
    Should Be Equal As Integers    ${data["question"]["quiz_id"]}    ${quiz_id}

Add Answer To Question
    &{headers}=    Create Dictionary
    ...    accept=application/json
    ...    Content-Type=application/json
    ...    Authorization=Bearer ${token}
    &{payload}=    Create Dictionary    text=Warszawa    is_correct=${True}    image_path=
    ${resp}=    POST On Session    api    /api/questions/${question_id}/answers    headers=${headers}    json=${payload}
    Should Be Equal As Integers    ${resp.status_code}    201
    ${data}=    Evaluate    json.loads('''${resp.text}''')    json
    Dictionary Should Contain Key    ${data}    answer
    ${answer_id}=    Set Variable    ${data["answer"]["id"]}
    Set Suite Variable    ${answer_id}

Is Correct Should Return True
    &{headers}=    Create Dictionary
    ...    accept=application/json
    ...    Authorization=Bearer ${token}
    ${resp}=    POST On Session    api    /api/questions/is-correct/${answer_id}    headers=${headers}
    Should Be Equal As Integers    ${resp.status_code}    200
    ${data}=    Evaluate    json.loads('''${resp.text}''')    json
    Should Be Equal    ${data["status"]}    success
    Should Be True    ${data["is_correct"]}
    Should Be Equal As Integers    ${data["answer_id"]}    ${answer_id}
    Should Be Equal As Integers    ${data["question_id"]}    ${question_id}

Is Correct Nonexistent Answer Returns 404
    &{headers}=    Create Dictionary    accept=application/json    Authorization=Bearer ${token}
    ${status}    ${err}=    Run Keyword And Ignore Error    POST On Session    api    /api/questions/is-correct/99999999    headers=${headers}
    Should Be Equal    ${status}    FAIL
    Should Match Regexp    ${err}    .*\\b404\\b.*

Get Comments Rating Summary
    &{headers}=    Create Dictionary    accept=application/json    Authorization=Bearer ${token}
    ${resp}=    GET On Session    api    /api/quizzes/${quiz_id}/comments/rating    headers=${headers}
    Should Be Equal As Integers    ${resp.status_code}    200
    ${data}=    Evaluate    json.loads('''${resp.text}''')    json
    Dictionary Should Contain Key    ${data}    average_rating
    Dictionary Should Contain Key    ${data}    total_comments
    Should Be Equal As Integers    ${data["quiz_id"]}    ${quiz_id}

Get Comment Details
    &{headers}=    Create Dictionary    accept=application/json    Authorization=Bearer ${token}
    ${resp}=    GET On Session    api    /api/quizzes/${quiz_id}/comments/${comment_id}    headers=${headers}
    Should Be Equal As Integers    ${resp.status_code}    200
    ${data}=    Evaluate    json.loads('''${resp.text}''')    json
    Should Be Equal As Integers    ${data["id"]}    ${comment_id}
    Should Be Equal As Integers    ${data["quiz_id"]}    ${quiz_id}

Update My Comment
    &{headers}=    Create Dictionary
    ...    accept=application/json
    ...    Content-Type=application/json
    ...    Authorization=Bearer ${token}
    &{payload}=    Create Dictionary    content=Zaktualizowana treść    rating=4
    ${resp}=    PUT On Session    api    /api/quizzes/${quiz_id}/comments/${comment_id}    headers=${headers}    json=${payload}
    Should Be Equal As Integers    ${resp.status_code}    200
    ${data}=    Evaluate    json.loads('''${resp.text}''')    json
    Should Be Equal    ${data["content"]}    Zaktualizowana treść
    Should Be Equal As Integers    ${data["rating"]}    4

Save Quiz Result Rejects Invalid Request
    &{headers}=    Create Dictionary
    ...    accept=application/json
    ...    Content-Type=application/json
    ...    Authorization=Bearer ${token}
    &{payload}=    Create Dictionary    quiz_id=${quiz_id}    points=85
    Log To Console    \n[REQUEST] POST ${BASE_URL}/api/quizzes/save-result
    Log To Console    [HEADERS] ${headers}
    Log To Console    [PAYLOAD] ${payload}
    ${status}    ${out}=    Run Keyword And Ignore Error    POST On Session    api    /api/quizzes/save-result    headers=${headers}    json=${payload}
    Log To Console    [RESULT] ${status}
    Run Keyword If    '${status}' == 'PASS'    Log To Console    [RESPONSE] ${out.status_code} | ${out.text}
    Run Keyword If    '${status}' == 'FAIL'    Log To Console    [ERROR] ${out}
    Run Keyword If    '${status}' == 'PASS'    Fail    Expected 422 but request succeeded: ${out.text}
    Should Match Regexp    ${out}    .*\\b422\\b.*

Get My Quiz Results
    &{headers}=    Create Dictionary    accept=application/json    Authorization=Bearer ${token}
    ${resp}=    GET On Session    api    /api/quiz-results    headers=${headers}
    Should Be Equal As Integers    ${resp.status_code}    200
    ${data}=    Evaluate    json.loads('''${resp.text}''')    json
    ${count}=    Get Length    ${data}
    Should Be True    ${count} > 0

Get Ranking Data
    &{headers}=    Create Dictionary    accept=application/json    Authorization=Bearer ${token}
    ${resp}=    GET On Session    api    /api/get-ranking-data    headers=${headers}
    Should Be True    ${resp.status_code} == 200 or ${resp.status_code} == 201

Delete My Comment
    &{headers}=    Create Dictionary    accept=application/json    Authorization=Bearer ${token}
    ${resp}=    DELETE On Session    api    /api/quizzes/${quiz_id}/comments/${comment_id}    headers=${headers}
    Should Be Equal As Integers    ${resp.status_code}    200

Delete Created Quiz
    &{headers}=    Create Dictionary    accept=application/json    Authorization=Bearer ${token}
    Log To Console    \n[REQUEST] DELETE ${BASE_URL}/api/quizzes/${quiz_id}
    Log To Console    [HEADERS] ${headers}

    ${st1}    ${out1}=    Run Keyword And Ignore Error    DELETE On Session    api    /api/quizzes/${quiz_id}    headers=${headers}
    Log To Console    [RESULT-1] ${st1}
    Run Keyword If    '${st1}' == 'PASS'    Log To Console    [RESPONSE-1] ${out1.status_code} | ${out1.text}
    Run Keyword If    '${st1}' == 'FAIL'    Log To Console    [ERROR-1] ${out1}

    IF    '${st1}' == 'PASS' and ${out1.status_code} != 404
        Should Be Equal As Integers    ${out1.status_code}    200
    ELSE
        Log To Console    [REQUEST] DELETE ${BASE_URL}/quizzes/${quiz_id}
        ${st2}    ${out2}=    Run Keyword And Ignore Error    DELETE On Session    api    /quizzes/${quiz_id}    headers=${headers}
        Log To Console    [RESULT-2] ${st2}
        Run Keyword If    '${st2}' == 'PASS'    Log To Console    [RESPONSE-2] ${out2.status_code} | ${out2.text}
        Run Keyword If    '${st2}' == 'FAIL'    Log To Console    [ERROR-2] ${out2}
        Should Be Equal    ${st2}    PASS
        Should Be Equal As Integers    ${out2.status_code}    200
    END
