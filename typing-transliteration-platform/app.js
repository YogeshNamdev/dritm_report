(function () {
  "use strict";

  const TRANSLITERATION_MODULE_ENABLED = false;

  /*
   * Shared Transliteration Helpers
   * These helpers remain active because Hindi typing practice uses roman Hindi
   * input and converts it to Devanagari for scoring.
   */
  const independentVowels = {
    a: "अ", aa: "आ", i: "इ", ee: "ई", ii: "ई", u: "उ", oo: "ऊ", uu: "ऊ",
    e: "ए", ai: "ऐ", o: "ओ", au: "औ", ri: "ऋ"
  };

  const vowelMatras = {
    a: "", aa: "ा", i: "ि", ee: "ी", ii: "ी", u: "ु", oo: "ू", uu: "ू",
    e: "े", ai: "ै", o: "ो", au: "ौ", ri: "ृ"
  };

  const consonants = {
    ksh: "क्ष", gy: "ज्ञ", jn: "ज्ञ", tr: "त्र",
    chh: "छ", kh: "ख", gh: "घ", ch: "च", jh: "झ", th: "थ", dh: "ध",
    ph: "फ", bh: "भ", sh: "श", ng: "ङ", ny: "ञ",
    q: "क", k: "क", g: "ग", c: "क", j: "ज", t: "त", d: "द", n: "न",
    p: "प", f: "फ", b: "ब", m: "म", y: "य", r: "र", l: "ल", v: "व",
    w: "व", s: "स", h: "ह", x: "क्स", z: "ज"
  };

  const hiVowels = {
    अ: "a", आ: "aa", इ: "i", ई: "ee", उ: "u", ऊ: "oo", ऋ: "ri",
    ए: "e", ऐ: "ai", ओ: "o", औ: "au"
  };

  const hiMatras = {
    "ा": "aa", "ि": "i", "ी": "ee", "ु": "u", "ू": "oo", "ृ": "ri",
    "े": "e", "ै": "ai", "ो": "o", "ौ": "au"
  };

  const hiConsonants = {
    क: "k", ख: "kh", ग: "g", घ: "gh", ङ: "ng",
    च: "ch", छ: "chh", ज: "j", झ: "jh", ञ: "ny",
    ट: "t", ठ: "th", ड: "d", ढ: "dh", ण: "n",
    त: "t", थ: "th", द: "d", ध: "dh", न: "n",
    प: "p", फ: "ph", ब: "b", भ: "bh", म: "m",
    य: "y", र: "r", ल: "l", व: "v", श: "sh",
    ष: "sh", स: "s", ह: "h", क्ष: "ksh", त्र: "tr", ज्ञ: "gy",
    क़: "k", ख़: "kh", ग़: "g", ज़: "z", ड़: "d", ढ़: "dh", फ़: "f"
  };

  const marks = {
    "ं": "n", "ँ": "n", "ः": "h", "़": "", "।": ".", "॥": "."
  };

  const enSamples = [
  "Consistent typing practice builds muscle memory and improves overall productivity. Small improvements every day create long-term results.",

  "Stay relaxed while typing and avoid rushing through the text. Accuracy and rhythm are more important than random speed bursts.",

  "A good typing habit can save hours of work over time. Proper finger placement and regular practice make a noticeable difference.",

  "The more carefully you type, the fewer mistakes you make. Confidence grows naturally when your hands follow a steady flow.",

  "Typing tests help measure progress in speed and accuracy. Reviewing mistakes after each session helps users improve faster.",

  "Professional communication becomes easier when typing feels natural. Clear focus and continuous practice are the keys to mastery.",

  "Improving typing speed requires patience and repetition. Daily practice sessions create better coordination and faster response time.",

  "Fast typing is useful in development, documentation, communication, and office work. Strong fundamentals always lead to better performance.",

  "Avoid looking at the keyboard too often while typing. Training your fingers to remember key positions improves efficiency greatly.",

  "Typing accuracy should always come before typing speed. Correct habits developed early help avoid errors in the future.",

  "Short and focused typing exercises are often more effective than long inconsistent sessions. Practice regularly to maintain progress.",

  "Every typing session is an opportunity to improve concentration, coordination, and confidence while working with digital systems.",

  "A calm mindset and proper sitting posture can improve typing performance significantly. Comfort plays an important role in consistency.",

  "Learning to type efficiently can improve communication speed and overall workflow in both personal and professional environments.",

  "The best way to improve typing is through regular practice, honest self-review, and gradual increases in difficulty over time."
];
const hiSamples = [
  "नियमित अभ्यास से टाइपिंग की गति और शुद्धता दोनों बेहतर होती हैं। ध्यान से पढ़ें और शांत गति से लिखें।",

  "हर दिन थोड़ा अभ्यास करने से उंगलियों की चाल मजबूत होती है। पहले सही लिखें, फिर गति अपने आप बढ़ेगी।",

  "हिंदी टाइपिंग में धैर्य और सही शब्द पहचान बहुत जरूरी है। अभ्यास के साथ आत्मविश्वास भी बढ़ता है।",

  "लगातार अभ्यास करने से टाइपिंग आसान और तेज होती जाती है। सही उंगलियों का उपयोग करना भी बहुत महत्वपूर्ण है।",

  "टाइपिंग सीखने के लिए ध्यान और नियमित अभ्यास दोनों जरूरी हैं। छोटी गलतियों को सुधारते रहें और आगे बढ़ते रहें।",

  "सही गति और कम गलतियों के साथ टाइप करना एक उपयोगी कौशल है जो हर क्षेत्र में मदद करता है।",

  "टाइपिंग करते समय जल्दबाजी न करें। पहले शुद्धता पर ध्यान दें और फिर धीरे-धीरे गति बढ़ाएं।",

  "अच्छी टाइपिंग आदतें आपके काम को तेज और आसान बना सकती हैं। नियमित अभ्यास से आत्मविश्वास बढ़ता है।",

  "हर टाइपिंग टेस्ट आपकी क्षमता को बेहतर बनाने का एक नया अवसर होता है। लगातार अभ्यास ही सफलता की कुंजी है।",

  "धैर्य और अनुशासन के साथ किया गया अभ्यास लंबे समय में शानदार परिणाम देता है।",

  "टाइपिंग स्पीड बढ़ाने के लिए रोजाना कुछ समय अभ्यास करना बहुत फायदेमंद होता है।",

  "हिंदी टाइपिंग में शब्दों की सही पहचान और ध्यानपूर्वक लिखना सबसे महत्वपूर्ण होता है।",

  "शांत मन और सही बैठने की स्थिति टाइपिंग प्रदर्शन को बेहतर बनाने में मदद करती है।",

  "गलतियों से सीखना और उन्हें सुधारना टाइपिंग कौशल को मजबूत बनाता है।",

  "नियमित अभ्यास से उंगलियों की गति और शब्दों की पहचान दोनों में सुधार आता है।"
];

  const state = {
    direction: "hi-en",
    language: "english",
    active: false,
    duration: 60,
    remaining: 60,
    startedAt: 0,
    timerId: null,
    target: "",
    sampleIndex: 0
  };

  const $ = (id) => document.getElementById(id);

  const elements = {
    practicePanel: $("practicePanel"),
    practiceLanguage: $("practiceLanguage"),
    durationSelect: $("durationSelect"),
    timeLeft: $("timeLeft"),
    wpmValue: $("wpmValue"),
    accuracyValue: $("accuracyValue"),
    mistakeValue: $("mistakeValue"),
    progressFill: $("progressFill"),
    targetDisplay: $("targetDisplay"),
    typingInput: $("typingInput"),
    typingInputLabel: $("typingInputLabel"),
    convertedPreview: $("convertedPreview"),
    convertedPreviewText: $("convertedPreviewText"),
    resultPanel: $("resultPanel"),
    historyList: $("historyList"),
    historySummary: $("historySummary")
  };

  /*
   * Transliteration Module
   * Disabled for now. To re-enable:
   * 1. Uncomment the Transliteration Module HTML block in index.html.
   * 2. Set TRANSLITERATION_MODULE_ENABLED to true.
   * 3. Uncomment the converter navigation in index.html.
   */
  const converterElements = TRANSLITERATION_MODULE_ENABLED ? {
    converterPanel: $("converterPanel"),
    sourceText: $("sourceText"),
    outputText: $("outputText"),
    sourceLabel: $("sourceLabel"),
    outputLabel: $("outputLabel"),
    copyStatus: $("copyStatus")
  } : {};

  function transliterateHindiToEnglish(text) {
    let output = "";

    for (let i = 0; i < text.length; i += 1) {
      const two = text.slice(i, i + 2);
      const char = text[i];
      const next = text[i + 1];

      if (hiConsonants[two]) {
        output += hiConsonants[two];
        i += 1;
        if (!hiMatras[text[i + 1]] && text[i + 1] !== "्") {
          output += "a";
        }
        continue;
      }

      if (hiConsonants[char]) {
        output += hiConsonants[char];
        if (next === "्") {
          i += 1;
        } else if (!hiMatras[next]) {
          output += "a";
        }
        continue;
      }

      if (hiMatras[char]) {
        output += hiMatras[char];
      } else if (hiVowels[char]) {
        output += hiVowels[char];
      } else if (marks[char] !== undefined) {
        output += marks[char];
      } else {
        output += char;
      }
    }

    return output;
  }

  function longestMatch(source, index, map) {
    const keys = Object.keys(map).sort((a, b) => b.length - a.length);
    const rest = source.slice(index).toLowerCase();
    return keys.find((key) => rest.startsWith(key)) || "";
  }

  function transliterateWordToHindi(word) {
    let output = "";
    let index = 0;
    let pendingConsonant = false;

    while (index < word.length) {
      const raw = word[index];

      if (!/[a-z]/i.test(raw)) {
        output += raw;
        pendingConsonant = false;
        index += 1;
        continue;
      }

      const vowel = longestMatch(word, index, independentVowels);
      const consonant = longestMatch(word, index, consonants);

      if (vowel && (!consonant || vowel.length >= consonant.length)) {
        if (pendingConsonant) {
          output += vowelMatras[vowel];
          pendingConsonant = false;
        } else {
          output += independentVowels[vowel];
        }
        index += vowel.length;
        continue;
      }

      if (consonant) {
        if (pendingConsonant) {
          output += "्";
        }
        output += consonants[consonant];
        pendingConsonant = true;
        index += consonant.length;
        continue;
      }

      output += raw;
      pendingConsonant = false;
      index += 1;
    }

    return output;
  }

  function transliterateEnglishToHindi(text) {
    return text.replace(/[A-Za-z]+|[^A-Za-z]+/g, (part) => {
      if (/^[A-Za-z]+$/.test(part)) {
        return transliterateWordToHindi(part);
      }
      return part;
    });
  }

  function convertText() {
    if (!TRANSLITERATION_MODULE_ENABLED || !converterElements.sourceText || !converterElements.outputText) {
      return;
    }
    const value = converterElements.sourceText.value;
    converterElements.outputText.value = state.direction === "hi-en"
      ? transliterateHindiToEnglish(value)
      : transliterateEnglishToHindi(value);
  }

  function setDirection(direction) {
    if (!TRANSLITERATION_MODULE_ENABLED) {
      return;
    }
    state.direction = direction;
    document.querySelectorAll("[data-direction]").forEach((button) => {
      button.classList.toggle("active", button.dataset.direction === direction);
    });
    converterElements.sourceLabel.textContent = direction === "hi-en" ? "Hindi input" : "English input";
    converterElements.outputLabel.textContent = direction === "hi-en" ? "English output" : "Hindi output";
    converterElements.sourceText.placeholder = direction === "hi-en" ? "यहां हिंदी लिखें..." : "Type roman Hindi here, for example: namaste";
    convertText();
  }

  function setMode(mode) {
    if (!TRANSLITERATION_MODULE_ENABLED) {
      return;
    }
    document.querySelectorAll("[data-mode]").forEach((button) => {
      button.classList.toggle("active", button.dataset.mode === mode);
    });
    converterElements.converterPanel.classList.toggle("active", mode === "converter");
    elements.practicePanel.classList.toggle("active", mode === "practice");
  }

  function getSamples() {
    return state.language === "hindi" ? hiSamples : enSamples;
  }

  function chooseTarget(next) {
    const samples = getSamples();
    if (next) {
      state.sampleIndex = (state.sampleIndex + 1) % samples.length;
    } else {
      state.sampleIndex = Math.min(state.sampleIndex, samples.length - 1);
    }
    state.target = samples[state.sampleIndex];
    renderTarget("");
  }

  function escapeHtml(value) {
    return value.replace(/[&<>"']/g, (char) => ({
      "&": "&amp;", "<": "&lt;", ">": "&gt;", "\"": "&quot;", "'": "&#039;"
    }[char]));
  }

  function getTypedForScoring() {
    const raw = elements.typingInput.value;
    if (state.language === "hindi") {
      return transliterateEnglishToHindi(raw);
    }
    return raw;
  }

  function renderTarget(typed) {
    const chars = Array.from(state.target);
    const typedChars = Array.from(typed);
    const html = chars.map((char, index) => {
      let className = "";
      if (index < typedChars.length) {
        className = typedChars[index] === char ? "correct" : "incorrect";
      } else if (index === typedChars.length) {
        className = "current";
      }
      return `<span class="${className}">${escapeHtml(char)}</span>`;
    }).join("");
    elements.targetDisplay.innerHTML = html;
  }

  function calculateStats() {
    const typed = getTypedForScoring();
    const typedChars = Array.from(typed);
    const targetChars = Array.from(state.target);
    let correct = 0;
    let mistakes = 0;

    typedChars.forEach((char, index) => {
      if (char === targetChars[index]) {
        correct += 1;
      } else {
        mistakes += 1;
      }
    });

    const elapsedSeconds = state.active
      ? Math.max(1, Math.round((Date.now() - state.startedAt) / 1000))
      : Math.max(1, state.duration - state.remaining);
    const minutes = elapsedSeconds / 60;
    const wpm = Math.round((correct / 5) / minutes);
    const accuracy = typedChars.length ? Math.max(0, Math.round((correct / typedChars.length) * 100)) : 100;

    return { typed, correct, mistakes, wpm, accuracy };
  }

  function updateStats() {
    const stats = calculateStats();
    renderTarget(stats.typed);
    elements.wpmValue.textContent = String(stats.wpm);
    elements.accuracyValue.textContent = `${stats.accuracy}%`;
    elements.mistakeValue.textContent = String(stats.mistakes);

    if (state.language === "hindi") {
      elements.convertedPreviewText.textContent = stats.typed || "Hindi conversion will appear as you type roman Hindi.";
    }

    if (state.active && stats.typed === state.target) {
      finishTest();
    }
  }

  function formatTime(seconds) {
    const mins = Math.floor(seconds / 60);
    const secs = seconds % 60;
    return `${String(mins).padStart(2, "0")}:${String(secs).padStart(2, "0")}`;
  }

  function setLanguage(language) {
    state.language = language;
    state.sampleIndex = 0;
    elements.convertedPreview.classList.toggle("hidden", language !== "hindi");
    elements.typingInputLabel.textContent = language === "hindi"
      ? "Type in roman Hindi; it is converted for scoring"
      : "Type the text here";
    elements.typingInput.placeholder = language === "hindi"
      ? "Example: niyamit abhyas se typing ki gati..."
      : "";
    resetTest(false);
    chooseTarget(false);
  }

  function startTest() {
    if (state.active) {
      return;
    }
    state.active = true;
    state.startedAt = Date.now();
    elements.typingInput.disabled = false;
    elements.typingInput.focus();
    $("startBtn").textContent = "Running";

    state.timerId = window.setInterval(() => {
      const elapsed = Math.floor((Date.now() - state.startedAt) / 1000);
      state.remaining = Math.max(0, state.duration - elapsed);
      elements.timeLeft.textContent = formatTime(state.remaining);
      elements.progressFill.style.width = `${Math.min(100, (elapsed / state.duration) * 100)}%`;
      updateStats();

      if (state.remaining <= 0) {
        finishTest();
      }
    }, 250);
  }

  function resetTest(clearResult) {
    window.clearInterval(state.timerId);
    state.active = false;
    state.duration = Number(elements.durationSelect.value);
    state.remaining = state.duration;
    state.startedAt = 0;
    state.timerId = null;
    elements.typingInput.value = "";
    elements.typingInput.disabled = false;
    elements.timeLeft.textContent = formatTime(state.duration);
    elements.wpmValue.textContent = "0";
    elements.accuracyValue.textContent = "100%";
    elements.mistakeValue.textContent = "0";
    elements.progressFill.style.width = "0%";
    $("startBtn").textContent = "Start Test";
    if (clearResult) {
      elements.resultPanel.classList.add("hidden");
      elements.resultPanel.innerHTML = "";
    }
    if (state.language === "hindi") {
      elements.convertedPreviewText.textContent = "Hindi conversion will appear as you type roman Hindi.";
    }
    renderTarget("");
  }

  function finishTest() {
    if (!state.active) {
      return;
    }
    window.clearInterval(state.timerId);
    state.active = false;
    state.remaining = Math.max(0, state.remaining);
    $("startBtn").textContent = "Start Test";
    const stats = calculateStats();
    const record = {
      date: new Date().toISOString(),
      language: state.language,
      duration: state.duration,
      wpm: stats.wpm,
      accuracy: stats.accuracy,
      mistakes: stats.mistakes,
      characters: stats.correct
    };
    saveHistory(record);
    showResult(record);
    renderHistory();
  }

  function getHistory() {
    try {
      return JSON.parse(localStorage.getItem("aksharlabTypingHistory") || "[]");
    } catch (error) {
      return [];
    }
  }

  function saveHistory(record) {
    const history = getHistory();
    history.unshift(record);
    localStorage.setItem("aksharlabTypingHistory", JSON.stringify(history.slice(0, 30)));
  }

  function showResult(record) {
    elements.resultPanel.classList.remove("hidden");
    elements.resultPanel.innerHTML = `
      <strong>Latest result</strong>
      ${record.wpm} WPM, ${record.accuracy}% accuracy, ${record.mistakes} mistakes
    `;
  }

  function renderHistory() {
    const history = getHistory();
    if (!history.length) {
      elements.historySummary.textContent = "No saved attempts yet.";
      elements.historyList.innerHTML = "";
      return;
    }

    const avgWpm = Math.round(history.reduce((sum, item) => sum + item.wpm, 0) / history.length);
    const avgAccuracy = Math.round(history.reduce((sum, item) => sum + item.accuracy, 0) / history.length);
    elements.historySummary.textContent = `${history.length} attempts, ${avgWpm} avg WPM, ${avgAccuracy}% avg accuracy.`;
    elements.historyList.innerHTML = history.map((item) => {
      const date = new Date(item.date).toLocaleString();
      const language = item.language === "hindi" ? "Hindi" : "English";
      return `
        <article class="history-item">
          <div>
            <strong>${item.wpm} WPM · ${item.accuracy}%</strong>
            <div class="history-meta">${language} · ${item.duration / 60} min · ${date}</div>
          </div>
          <div class="history-meta">${item.mistakes} mistakes</div>
        </article>
      `;
    }).join("");
  }

  /*
   * Transliteration Module Event Listeners
   * Disabled for now. This block can stay guarded as-is, or be uncommented
   * alongside the converter HTML by setting TRANSLITERATION_MODULE_ENABLED to true.
   */
  if (TRANSLITERATION_MODULE_ENABLED) {
    document.querySelectorAll("[data-mode]").forEach((button) => {
      button.addEventListener("click", () => setMode(button.dataset.mode));
    });

    document.querySelectorAll("[data-direction]").forEach((button) => {
      button.addEventListener("click", () => setDirection(button.dataset.direction));
    });

    converterElements.sourceText.addEventListener("input", convertText);

    $("swapBtn").addEventListener("click", () => {
      const oldOutput = converterElements.outputText.value;
      const nextDirection = state.direction === "hi-en" ? "en-hi" : "hi-en";
      setDirection(nextDirection);
      converterElements.sourceText.value = oldOutput;
      convertText();
    });

    $("copyBtn").addEventListener("click", async () => {
      try {
        await navigator.clipboard.writeText(converterElements.outputText.value);
        converterElements.copyStatus.textContent = "Copied.";
      } catch (error) {
        converterElements.outputText.select();
        document.execCommand("copy");
        converterElements.copyStatus.textContent = "Copied.";
      }
      window.setTimeout(() => {
        converterElements.copyStatus.textContent = "";
      }, 1800);
    });

    $("clearConverterBtn").addEventListener("click", () => {
      converterElements.sourceText.value = "";
      converterElements.outputText.value = "";
      converterElements.sourceText.focus();
    });
  }

  /*
   * Typing Practice Module Event Listeners
   */
  elements.practiceLanguage.addEventListener("change", () => setLanguage(elements.practiceLanguage.value));
  elements.durationSelect.addEventListener("change", () => resetTest(true));
  elements.typingInput.addEventListener("input", updateStats);
  $("startBtn").addEventListener("click", startTest);
  $("resetBtn").addEventListener("click", () => resetTest(true));
  $("newTextBtn").addEventListener("click", () => {
    resetTest(true);
    chooseTarget(true);
  });
  $("clearHistoryBtn").addEventListener("click", () => {
    localStorage.removeItem("aksharlabTypingHistory");
    renderHistory();
  });

  if (TRANSLITERATION_MODULE_ENABLED) {
    setDirection("hi-en");
    setMode("practice");
  }
  setLanguage("english");
  chooseTarget(false);
  resetTest(true);
  renderHistory();
}());
