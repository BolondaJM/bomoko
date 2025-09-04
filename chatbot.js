// Simple floating chatbot widget with keyword-based matching
(function() {
    console.log('DRC Embassy Chatbot loaded successfully!'); // Debug log
    
    // Create style
    var style = document.createElement('style');
    style.innerHTML = `
    .chatbot-fab {
        position: fixed;
        bottom: 28px;
        right: 28px;
        z-index: 9999;
        background: #003366;
        color: #fff;
        border: none;
        border-radius: 50px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.18);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.7rem 1.2rem 0.7rem 0.9rem;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
    }
    .chatbot-fab:hover {
        background: #0055a5;
    }
    .chatbot-fab svg {
        width: 1.3em;
        height: 1.3em;
        vertical-align: middle;
        fill: #fff;
    }
    .chatbot-window {
        position: fixed;
        bottom: 80px;
        right: 32px;
        width: 320px;
        max-width: 95vw;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.18);
        z-index: 10000;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        border: 1px solid #e0e6ef;
        animation: chatbot-pop 0.2s;
    }
    @keyframes chatbot-pop {
        from { transform: scale(0.8); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
    .chatbot-header {
        background: #003366;
        color: #fff;
        padding: 0.7rem 1rem;
        font-weight: 600;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .chatbot-close {
        background: none;
        border: none;
        color: #fff;
        font-size: 1.2rem;
        cursor: pointer;
    }
    .chatbot-body {
        flex: 1;
        padding: 1rem;
        font-size: 0.98rem;
        background: #f4f8fb;
        overflow-y: auto;
        min-height: 120px;
        max-height: 260px;
    }
    .chatbot-input {
        display: flex;
        border-top: 1px solid #e0e6ef;
        background: #fff;
    }
    .chatbot-input input {
        flex: 1;
        border: none;
        padding: 0.7rem;
        font-size: 1rem;
        outline: none;
        background: #fff;
    }
    .chatbot-input button {
        background: #003366;
        color: #fff;
        border: none;
        padding: 0 1.1rem;
        font-size: 1rem;
        cursor: pointer;
        transition: background 0.2s;
    }
    .chatbot-input button:hover {
        background: #0055a5;
    }
    .chatbot-msg {
        margin-bottom: 0.7rem;
        padding: 0.5rem 0.8rem;
        border-radius: 8px;
        background: #e9eef6;
        color: #222;
        max-width: 90%;
        word-break: break-word;
    }
    .chatbot-msg.user {
        background: #003366;
        color: #fff;
        align-self: flex-end;
    }
    .chatbot-msg.bot {
        background: #e9eef6;
        color: #222;
    }
    .chatbot-msg.typing {
        background: #e9eef6;
        color: #666;
        font-style: italic;
    }
    `;
    document.head.appendChild(style);

    // Knowledge base with questions and answers
    const knowledgeBase = [
        {
            question: "Where is the DRC Embassy located in Kenya?",
            answer: "The DRC Embassy is located in Riverside Drive, Nairobi, Kenya.",
            keywords: ["where", "location", "located", "embassy", "nairobi", "kenya", "address", "place"]
        },
        {
            question: "What are your working hours?",
            answer: "The Embassy is open Monday to Friday from 9:00 AM to 4:00 PM, except on public holidays.",
            keywords: ["working", "hours", "open", "time", "schedule", "monday", "friday", "9:00", "4:00", "holiday"]
        },
        {
            question: "How can I apply for a DRC visa in Kenya?",
            answer: "You can apply at the Embassy by filling out the visa form, submitting required documents, and paying the visa fee.",
            keywords: ["apply", "visa", "application", "form", "documents", "fee", "how", "process"]
        },
        {
            question: "How long does it take to process a visa application?",
            answer: "Processing usually takes 5–7 working days, depending on the type of visa.",
            keywords: ["how long", "process", "processing", "time", "days", "5", "7", "working", "visa"]
        },
        {
            question: "Can Kenyan citizens travel to DRC without a visa?",
            answer: "No, Kenyan citizens require a visa to travel to the Democratic Republic of Congo.",
            keywords: ["kenyan", "citizens", "travel", "without", "visa", "require", "need", "democratic", "congo"]
        },
        {
            question: "What documents are needed to apply for a DRC visa?",
            answer: "A valid passport, completed visa application form, passport-size photos, invitation letter or travel itinerary, and proof of funds.",
            keywords: ["documents", "needed", "required", "passport", "photos", "invitation", "letter", "itinerary", "proof", "funds"]
        },
        {
            question: "Does the Embassy issue emergency travel documents?",
            answer: "Yes, Congolese citizens in Kenya can apply for an emergency travel document if their passport is lost or expired.",
            keywords: ["emergency", "travel", "document", "lost", "expired", "passport", "congolese", "citizens"]
        },
        {
            question: "Can I register as a Congolese living in Kenya?",
            answer: "Yes, the Embassy encourages all Congolese nationals in Kenya to register for consular support and statistics.",
            keywords: ["register", "congolese", "living", "kenya", "nationals", "consular", "support", "statistics"]
        },
        {
            question: "How do I book an appointment at the Embassy?",
            answer: "Appointments can be booked by calling the Embassy, sending an email, or through the Embassy's official website (if available).",
            keywords: ["book", "appointment", "calling", "email", "website", "schedule", "meeting"]
        },
        {
            question: "Does the Embassy provide notary or authentication services?",
            answer: "Yes, the Embassy provides services such as document authentication, legalization, and consular letters.",
            keywords: ["notary", "authentication", "services", "document", "legalization", "consular", "letters"]
        }
    ];

    // Default responses for when no match is found
    const defaultResponses = [
        "I'm sorry, I don't have specific information about that. Please contact the Embassy directly for assistance.",
        "That's a good question, but I don't have the answer in my knowledge base. You can call the Embassy for more information.",
        "I'm not sure about that. Please visit the Embassy or check the website for the most accurate information.",
        "I don't have information about that specific topic. You may want to contact the Embassy directly.",
        "I'm here to help with common questions about the DRC Embassy. For specific inquiries, please contact the Embassy."
    ];

    // Function to calculate similarity between user input and keywords
    function calculateSimilarity(userInput, keywords) {
        const inputWords = userInput.toLowerCase().split(/\s+/);
        const keywordWords = keywords.map(k => k.toLowerCase());
        
        let matchCount = 0;
        let totalKeywords = keywordWords.length;
        
        for (let inputWord of inputWords) {
            for (let keyword of keywordWords) {
                // Exact match
                if (inputWord === keyword) {
                    matchCount += 2;
                }
                // Partial match (keyword contains input word or vice versa)
                else if (inputWord.includes(keyword) || keyword.includes(inputWord)) {
                    matchCount += 1;
                }
                // Check for compound words (e.g., "working hours" vs "working" and "hours")
                else if (keyword.includes(' ') && inputWord.length > 3) {
                    const keywordParts = keyword.split(' ');
                    for (let part of keywordParts) {
                        if (inputWord.includes(part) && part.length > 2) {
                            matchCount += 0.5;
                        }
                    }
                }
            }
        }
        
        return matchCount / totalKeywords;
    }

    // Function to find the best matching answer
    function findBestMatch(userInput) {
        let bestMatch = null;
        let bestScore = 0;
        
        for (let item of knowledgeBase) {
            const score = calculateSimilarity(userInput, item.keywords);
            if (score > bestScore) {
                bestScore = score;
                bestMatch = item;
            }
        }
        
        // Only return a match if the score is above a threshold
        if (bestScore >= 0.3) {
            return bestMatch;
        }
        
        return null;
    }

    // Function to get a random default response
    function getRandomDefaultResponse() {
        const randomIndex = Math.floor(Math.random() * defaultResponses.length);
        return defaultResponses[randomIndex];
    }

    // Create FAB
    var fab = document.createElement('button');
    fab.className = 'chatbot-fab';
    fab.innerHTML = `<svg viewBox="0 0 24 24"><path d="M12 3C6.48 3 2 6.92 2 12c0 2.08.81 3.97 2.19 5.44L2 21l3.7-1.16C7.7 20.6 9.78 21 12 21c5.52 0 10-3.92 10-9s-4.48-9-10-9zm0 16c-2.01 0-3.89-.49-5.37-1.34l-.38-.22-2.22.7.7-2.03-.25-.4C4.08 15.01 3.5 13.56 3.5 12c0-4.08 4.03-7.5 8.5-7.5s8.5 3.42 8.5 7.5-4.03 7.5-8.5 7.5z"/><circle cx="8.5" cy="11.5" r="1.5"/><circle cx="15.5" cy="11.5" r="1.5"/></svg> Ask Me`;
    document.body.appendChild(fab);

    // Create chat window
    var chatWindow = document.createElement('div');
    chatWindow.className = 'chatbot-window';
    chatWindow.style.display = 'none';
    chatWindow.innerHTML = `
        <div class="chatbot-header">DRC Embassy Assistant <button class="chatbot-close" title="Close">&times;</button></div>
        <div class="chatbot-body"></div>
        <form class="chatbot-input">
            <input type="text" placeholder="Ask about visas, appointments, documents..." autocomplete="off" required />
            <button type="submit">Send</button>
        </form>
    `;
    document.body.appendChild(chatWindow);

    var closeBtn = chatWindow.querySelector('.chatbot-close');
    var body = chatWindow.querySelector('.chatbot-body');
    var inputForm = chatWindow.querySelector('.chatbot-input');
    var input = inputForm.querySelector('input');

    // Add welcome message
    function addWelcomeMessage() {
        var welcomeMsg = document.createElement('div');
        welcomeMsg.className = 'chatbot-msg bot';
        welcomeMsg.innerHTML = `
            <strong>Welcome to DRC Embassy Assistant!</strong><br><br>
            I can help you with questions about:<br>
            • Embassy location and hours<br>
            • Visa applications and requirements<br>
            • Document services<br>
            • Appointments and registration<br><br>
            How can I assist you today?
        `;
        body.appendChild(welcomeMsg);
    }

    fab.onclick = function() {
        chatWindow.style.display = 'flex';
        if (body.children.length === 0) {
            addWelcomeMessage();
        }
        input.focus();
    };
    
    closeBtn.onclick = function() {
        chatWindow.style.display = 'none';
    };
    
    inputForm.onsubmit = function(e) {
        e.preventDefault();
        var msg = input.value.trim();
        if (!msg) return;
        
        console.log('User input:', msg); // Debug log
        
        // Add user message
        var userMsg = document.createElement('div');
        userMsg.className = 'chatbot-msg user';
        userMsg.textContent = msg;
        body.appendChild(userMsg);
        input.value = '';
        body.scrollTop = body.scrollHeight;
        
        // Show typing indicator
        var typingMsg = document.createElement('div');
        typingMsg.className = 'chatbot-msg typing';
        typingMsg.textContent = 'Typing...';
        body.appendChild(typingMsg);
        body.scrollTop = body.scrollHeight;
        
        // Process the message and respond
        setTimeout(function() {
            // Remove typing indicator
            body.removeChild(typingMsg);
            
            // Find best match
            var match = findBestMatch(msg);
            console.log('Best match:', match); // Debug log
            
            var botMsg = document.createElement('div');
            botMsg.className = 'chatbot-msg bot';
            
            if (match) {
                botMsg.innerHTML = `<strong>${match.question}</strong><br><br>${match.answer}`;
                console.log('Responding with match:', match.question); // Debug log
            } else {
                var defaultResponse = getRandomDefaultResponse();
                botMsg.textContent = defaultResponse;
                console.log('Responding with default:', defaultResponse); // Debug log
            }
            
            body.appendChild(botMsg);
            body.scrollTop = body.scrollHeight;
        }, 1000);
    };
})(); 