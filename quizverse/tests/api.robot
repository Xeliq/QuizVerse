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
