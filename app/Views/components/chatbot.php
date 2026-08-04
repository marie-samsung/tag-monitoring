<?php
$chatTitle = $chatTitle ?? 'Script AI';
$chatEndpoint = $chatEndpoint ?? site_url('tags/ai-chat');
$historyEndpoint = $historyEndpoint ?? site_url('tags/ai-history/');
$conversationEndpoint = $conversationEndpoint ?? site_url('tags/ai-conversation/');
$contextId = $contextId ?? 0;
$user = session()->get('user_name') ?? 'User';
$aiModels = $aiModels ?? [];
?>

<style>
/* Apple Style Chatbot */
.apple-chat-widget {
    position: fixed;
    bottom: 30px;
    right: 24px;
    z-index: 1050;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
}

.apple-chat-window {
    width: 550px;
    height: 550px;
    min-width: 550px;
    min-height: 400px;
    max-width: 90vw;
    max-height: 85vh;
    resize: both;
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-radius: 20px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0,0,0,0.05);
    border: 1px solid rgba(255, 255, 255, 0.5);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    margin-bottom: 16px;
    transition: opacity 0.3s cubic-bezier(0.25, 0.8, 0.25, 1), transform 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    transform-origin: bottom right;
    opacity: 0;
    transform: scale(0.95) translateY(10px);
    pointer-events: none;
}

.apple-chat-window.active {
    opacity: 1;
    transform: scale(1) translateY(0);
    pointer-events: auto;
}

.apple-chat-header {
    padding: 16px 20px;
    background: rgba(255, 255, 255, 0.5);
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.apple-model-select {
    display: none; /* Select2 will replace this */
}

.apple-chat-header .select2-container--default .select2-selection--single {
    border-radius: 12px;
    border: 1px solid rgba(0,0,0,0.1);
    background: rgba(255,255,255,0.8);
    height: 36px;
    display: flex;
    align-items: center;
}

.apple-chat-header .select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #1d1d1f;
    font-size: 0.85rem;
    font-weight: 500;
    line-height: 36px;
    padding-left: 12px;
}

.apple-chat-header .select2-dropdown {
    border-radius: 12px;
    border: 1px solid rgba(0,0,0,0.1);
    box-shadow: 0 10px 24px rgba(0,0,0,0.1);
    background: #ffffff;
    color: #1d1d1f;
    overflow: hidden;
    z-index: 9999;
}

.apple-chat-header .select2-results__option {
    padding: 8px 12px;
    border-bottom: 1px solid rgba(0,0,0,0.05);
}

.ai-model-item {
    padding: 2px 0;
}
.ai-model-title {
    font-weight: 600;
    color: #1d1d1f;
    font-size: 0.9rem;
}
.ai-model-desc {
    font-size: 0.75rem;
    color: #6c757d;
    white-space: normal;
    line-height: 1.3;
    margin-top: 3px;
}

/* Ensure text is readable when highlighted */
.select2-results__option--highlighted,
.select2-results__option[aria-selected="true"] {
    background-color: #007aff !important;
}
.select2-results__option--highlighted .ai-model-title,
.select2-results__option--highlighted .ai-model-desc,
.select2-results__option[aria-selected="true"] .ai-model-title,
.select2-results__option[aria-selected="true"] .ai-model-desc {
    color: #ffffff !important;
}

.apple-chat-header .select2-container {
    width: 240px !important;
    max-width: 100%;
}

.apple-chat-title {
    font-weight: 600;
    font-size: 1.1rem;
    color: #1d1d1f;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.apple-chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.chat-message {
    max-width: 85%;
    padding: 12px 16px;
    border-radius: 18px;
    font-size: 0.95rem;
    line-height: 1.4;
    overflow-wrap: break-word;
    word-break: normal;
}

.chat-message p {
    margin-bottom: 0.5rem;
}
.chat-message p:last-child {
    margin-bottom: 0;
}
.chat-message pre {
    background: rgba(0,0,0,0.05);
    padding: 8px;
    border-radius: 8px;
    font-size: 0.85rem;
    overflow-x: auto;
    margin-top: 0.5rem;
    margin-bottom: 0.5rem;
}
.chat-message code {
    font-family: monospace;
    background: rgba(0,0,0,0.05);
    padding: 2px 4px;
    border-radius: 4px;
    font-size: 0.85rem;
}
.chat-message.user code {
    background: rgba(255,255,255,0.2);
}
.chat-message ul, .chat-message ol {
    margin-bottom: 0.5rem;
    padding-left: 1.5rem;
}

.chat-message.bot {
    background: #f5f5f7;
    color: #1d1d1f;
    align-self: flex-start;
    border-bottom-left-radius: 4px;
}

.chat-message.user {
    background: #007aff;
    color: #fff;
    align-self: flex-end;
    border-bottom-right-radius: 4px;
}

.typing-indicator {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    height: 18px;
    padding: 0 4px;
}

.typing-indicator span {
    width: 6px;
    height: 6px;
    background-color: #8e8e93;
    border-radius: 50%;
    animation: typing 1.4s infinite ease-in-out both;
}

.typing-indicator span:nth-child(1) {
    animation-delay: -0.32s;
}

.typing-indicator span:nth-child(2) {
    animation-delay: -0.16s;
}

@keyframes typing {
    0%, 80%, 100% { transform: scale(0); }
    40% { transform: scale(1); }
}

.apple-chat-footer {
    padding: 16px 20px;
    background: rgba(255, 255, 255, 0.5);
    border-top: 1px solid rgba(0, 0, 0, 0.05);
    display: flex;
    gap: 10px;
    align-items: center;
}

.apple-chat-input {
    flex: 1;
    border: 1px solid rgba(0,0,0,0.1);
    border-radius: 20px;
    padding: 10px 16px;
    font-size: 0.95rem;
    outline: none;
    background: rgba(255,255,255,0.9);
    transition: border-color 0.2s;
}

.apple-chat-input:focus {
    border-color: #007aff;
}

.apple-chat-send {
    background: #007aff;
    color: white;
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.2s;
}

.apple-chat-send:hover {
    background: #0056b3;
}

.apple-chat-toggle {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: whitesmoke;
    color: #3b82f6;
    border: none;
    box-shadow: 0 4px 14px rgba(0,0,0,0.15);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    transition: transform 0.2s, background 0.2s;
}

.apple-chat-toggle:hover {
    transform: scale(1.05);
    background: white;
}
</style>

<div class="apple-chat-widget">
    <div class="apple-chat-window" id="appleChatWindow">
        <div class="apple-chat-header">
            <h3 class="apple-chat-title">
                <i class="bi bi-robot"></i> <?= esc($chatTitle) ?>
            </h3>
            <div class="d-flex align-items-center gap-2">
                <select class="apple-model-select" id="aiModelSelect">
                    <?php if (!empty($aiModels)): ?>
                        <?php foreach ($aiModels as $model): ?>
                            <?php 
                            $modelName = 'Unknown Model';
                            $modelDesc = '';
                            if (isset($model['name']) && is_array($model['name'])) {
                                foreach ($model['name'] as $nameObj) {
                                    $langCode = strtolower($nameObj['languagecode'] ?? $nameObj['languageCode'] ?? $nameObj['language'] ?? '');
                                    if ($langCode === 'en' || strpos($langCode, 'en') !== false) {
                                        $modelName = $nameObj['content'] ?? $nameObj['conent'] ?? $modelName;
                                        break;
                                    }
                                }
                                if ($modelName === 'Unknown Model' && !empty($model['name'][0])) {
                                    $modelName = $model['name'][0]['content'] ?? $model['name'][0]['conent'] ?? $modelName;
                                }
                            }
                            if (isset($model['description']) && is_array($model['description'])) {
                                foreach ($model['description'] as $descObj) {
                                    $langCode = strtolower($descObj['languagecode'] ?? $descObj['languageCode'] ?? $descObj['language'] ?? '');
                                    if ($langCode === 'en' || strpos($langCode, 'en') !== false) {
                                        $modelDesc = $descObj['content'] ?? $descObj['conent'] ?? '';
                                        break;
                                    }
                                }
                                if (empty($modelDesc) && !empty($model['description'][0])) {
                                    $modelDesc = $model['description'][0]['content'] ?? $model['description'][0]['conent'] ?? '';
                                }
                            }
                            ?>
                            <option value="<?= esc($model['modelId'] ?? '') ?>" data-description="<?= esc($modelDesc) ?>"><?= esc($modelName) ?></option>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="">No models available</option>
                    <?php endif; ?>
                </select>
                <button class="btn btn-sm btn-light border d-flex align-items-center" id="aiChatHistoryBtn" title="History"><i class="bi bi-clock-history"></i></button>
                <button class="btn btn-sm btn-light border d-flex align-items-center" id="aiChatNewBtn" title="New Chat"><i class="bi bi-plus-lg"></i></button>
            </div>

<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dompurify/dist/purify.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
        const chatToggle = document.getElementById('appleChatToggle');
        const chatWindow = document.getElementById('appleChatWindow');
        const chatInput = document.getElementById('appleChatInput');
        const chatSend = document.getElementById('appleChatSend');
        const chatMessages = document.getElementById('appleChatMessages');
        const modelSelect = $('#aiModelSelect');
        const historyBtn = document.getElementById('aiChatHistoryBtn');
        const newBtn = document.getElementById('aiChatNewBtn');
        const historyPanel = document.getElementById('appleChatHistoryPanel');
        const historyClose = document.getElementById('appleChatHistoryClose');
        const historyList = document.getElementById('appleChatHistoryList');
        
        let currentConversationId = null;
        const tagId = <?= esc($contextId) ?>;
        
        historyBtn.addEventListener('click', loadHistoryList);
        historyClose.addEventListener('click', () => historyPanel.style.display = 'none');
        newBtn.addEventListener('click', () => {
            currentConversationId = null;
            chatMessages.innerHTML = '<div class="chat-message bot">Hello <?= $user ?>! Do you need any script assistance today? 😊</div>';
            historyPanel.style.display = 'none';
        });

        function loadHistoryList() {
            historyList.innerHTML = '<div class="p-3 text-center text-muted small">Loading...</div>';
            historyPanel.style.display = 'flex';
            
            fetch('<?= $historyEndpoint ?>' + tagId)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success' && data.data && data.data.length > 0) {
                        historyList.innerHTML = data.data.map(conv => `
                            <button type="button" class="list-group-item list-group-item-action border-0 py-2 ai-history-item" data-id="${conv.id}">
                                <div class="d-flex w-100 justify-content-between align-items-center">
                                    <h6 class="mb-0 text-truncate" style="max-width: 75%; font-size: 0.85rem;">${conv.title || 'Conversation'}</h6>
                                    <small class="text-muted text-end" style="font-size: 0.7rem; min-width: 60px;">${new Date(conv.updated_at).toLocaleDateString()}</small>
                                </div>
                            </button>
                        `).join('');
                        
                        document.querySelectorAll('.ai-history-item').forEach(btn => {
                            btn.addEventListener('click', function() {
                                loadConversation(this.dataset.id);
                            });
                        });
                    } else {
                        historyList.innerHTML = '<div class="p-3 text-center text-muted small">No past conversations found.</div>';
                    }
                })
                .catch(err => {
                    historyList.innerHTML = '<div class="p-3 text-center text-danger small">Failed to load history.</div>';
                });
        }

        function loadConversation(id) {
            historyPanel.style.display = 'none';
            chatMessages.innerHTML = '<div class="p-3 text-center text-muted small">Loading conversation...</div>';
            
            fetch('<?= $conversationEndpoint ?>' + id)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        currentConversationId = data.data.id;
                        chatMessages.innerHTML = '';
                        try {
                            const messages = JSON.parse(data.data.messages);
                            messages.forEach(msg => {
                                if (msg.role === 'user') {
                                    appendMessage(msg.content, 'user');
                                } else if (msg.role === 'assistant') {
                                    const elem = appendMessage('', 'bot');
                                    if (typeof marked !== 'undefined' && typeof DOMPurify !== 'undefined') {
                                        elem.innerHTML = DOMPurify.sanitize(marked.parse(msg.content));
                                    } else {
                                        elem.innerText = msg.content;
                                    }
                                    
                                    if (msg.reasoning_content) {
                                        const infoIcon = document.createElement('i');
                                        infoIcon.className = 'bi bi-info-circle-fill text-muted';
                                        infoIcon.style.cursor = 'help';
                                        infoIcon.style.fontSize = '1.1rem';
                                        infoIcon.title = msg.reasoning_content;
                                        elem.parentElement.appendChild(infoIcon);
                                    }
                                }
                            });
                        } catch (e) {
                            chatMessages.innerHTML = '<div class="p-3 text-center text-danger small">Failed to parse conversation.</div>';
                        }
                    }
                });
        }

        // Make Chat Window Draggable
        const chatHeader = document.querySelector('.apple-chat-header');
        let isDragging = false;
        let dragOffsetX = 0;
        let dragOffsetY = 0;

        chatHeader.style.cursor = 'move';
        
        chatHeader.addEventListener('mousedown', (e) => {
            // Ignore drag if clicking on the select dropdown
            if (e.target.closest('.apple-model-select') || e.target.closest('.select2-container')) {
                return;
            }
            
            isDragging = true;
            const rect = chatWindow.getBoundingClientRect();
            
            // Switch to fixed positioning if not already
            if (chatWindow.style.position !== 'fixed') {
                chatWindow.style.position = 'fixed';
                chatWindow.style.margin = '0';
                chatWindow.style.bottom = 'auto';
                chatWindow.style.right = 'auto';
                chatWindow.style.left = rect.left + 'px';
                chatWindow.style.top = rect.top + 'px';
            }
            
            dragOffsetX = e.clientX - chatWindow.getBoundingClientRect().left;
            dragOffsetY = e.clientY - chatWindow.getBoundingClientRect().top;
            document.body.style.userSelect = 'none'; // Prevent text selection while dragging
        });

        document.addEventListener('mousemove', (e) => {
            if (isDragging) {
                chatWindow.style.left = (e.clientX - dragOffsetX) + 'px';
                chatWindow.style.top = (e.clientY - dragOffsetY) + 'px';
            }
        });

        document.addEventListener('mouseup', () => {
            isDragging = false;
            document.body.style.userSelect = '';
        });

        // Initialize Select2 for the AI models
        modelSelect.select2({
            width: '100%',
            dropdownParent: $('#appleChatWindow'),
            placeholder: "Select an AI model",
            templateResult: function (data) {
                if (!data.id) return data.text;
                var desc = $(data.element).data('description') || '';
                var descHtml = desc ? '<div class="ai-model-desc">' + desc + '</div>' : '';
                return $(
                    '<div class="ai-model-item">' +
                    '<div class="ai-model-title">' + data.text + '</div>' +
                    descHtml +
                    '</div>'
                );
            },
            templateSelection: function(data) {
                return data.text;
            }
        });

        // Toggle Chat Window
        chatToggle.addEventListener('click', () => {
            chatWindow.classList.toggle('active');
            if (chatWindow.classList.contains('active')) {
                chatInput.focus();
            }
        });

        // Handle Send Message
        function sendMessage() {
            const text = chatInput.value.trim();
            if (!text) return;

            const modelId = modelSelect.val();
            if (!modelId) {
                appendMessage('Please select a model first.', 'bot');
                return;
            }

            // Add user message to UI
            appendMessage(text, 'user');
            chatInput.value = '';

            // Get script content from monaco editor if available
            let scriptContent = '';
            if (window.editorInstance) {
                scriptContent = window.editorInstance.getValue();
            }

            // Show typing indicator
            const botMsgElem = appendMessage('', 'bot');
            botMsgElem.innerHTML = '<div class="typing-indicator"><span></span><span></span><span></span></div>';

            const chatEndpoint = '<?= $chatEndpoint ?>'; 
            
            fetch(chatEndpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    modelId: modelId,
                    message: text,
                    context: scriptContent, // Send current script context to the LLM
                    conversationId: currentConversationId,
                    tagId: tagId
                })
            })
            .then(res => res.json())
            .then(data => {
                let reply = "I'm sorry, I couldn't process your request.";
                let reasoning = null;
                
                if (data && data.choices && data.choices.length > 0 && data.choices[0].message) {
                    reply = data.choices[0].message.content || reply;
                    reasoning = data.choices[0].message.reasoning_content || null;
                } else if (data.reply || data.message) {
                    reply = data.reply || data.message;
                }

                if (typeof marked !== 'undefined' && typeof DOMPurify !== 'undefined') {
                    botMsgElem.innerHTML = DOMPurify.sanitize(marked.parse(reply));
                } else {
                    botMsgElem.innerText = reply;
                }
                
                if (reasoning && botMsgElem.parentElement.classList.contains('chat-wrapper')) {
                    const infoIcon = document.createElement('i');
                    infoIcon.className = 'bi bi-info-circle-fill text-muted';
                    infoIcon.style.cursor = 'help';
                    infoIcon.style.fontSize = '1.1rem';
                    infoIcon.title = reasoning; // Hover to see reasoning
                    botMsgElem.parentElement.appendChild(infoIcon);
                }

                if (data.conversationId) {
                    currentConversationId = data.conversationId;
                }

                chatMessages.scrollTop = chatMessages.scrollHeight;
            })
            .catch(err => {
                console.error('Error sending message:', err);
                botMsgElem.innerText = "Error communicating with the LLM API. Check console for details.";
                chatMessages.scrollTop = chatMessages.scrollHeight;
            });
        }

        function appendMessage(text, sender) {
            const wrapper = document.createElement('div');
            wrapper.className = 'chat-wrapper';
            wrapper.style.display = 'flex';
            wrapper.style.alignItems = 'center';
            wrapper.style.gap = '8px';
            wrapper.style.alignSelf = sender === 'user' ? 'flex-end' : 'flex-start';
            wrapper.style.maxWidth = '100%';

            const msgDiv = document.createElement('div');
            msgDiv.className = `chat-message ${sender}`;
            msgDiv.innerText = text;
            msgDiv.style.alignSelf = 'auto'; // override class styling
            msgDiv.style.margin = '0';
            
            wrapper.appendChild(msgDiv);
            chatMessages.appendChild(wrapper);
            chatMessages.scrollTop = chatMessages.scrollHeight;
            
            return msgDiv;
        }

        chatSend.addEventListener('click', sendMessage);
        chatInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
    });
</script>
