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

  /*
   * Kruti Dev 010 / Remington-Gail key layout.
   * Compiled from two reference keyboard charts and a Kruti Dev typing PDF
   * the user supplied, cross-checked against each other where they
   * overlapped. Output is real Unicode Devanagari (not the legacy Kruti Dev
   * byte encoding), so it renders correctly in any standard font without
   * needing the proprietary Kruti Dev .ttf file.
   * "preBase: true" marks the well-known Kruti Dev quirk where the short i
   * matra (ि) is struck BEFORE its consonant, matching physical typewriter
   * order, even though it must land AFTER the consonant in Unicode text.
   * A few rarer shift combinations were not independently confirmable from
   * the source material and are left unmapped rather than guessed.
   */
  const KRUTI_KEYMAP = {
    "1": { base: "१", shift: "!" }, "2": { base: "२", shift: "@" },
    "3": { base: "३", shift: "#" }, "4": { base: "४", shift: "$" },
    "5": { base: "५", shift: "%" }, "6": { base: "६", shift: "^" },
    "7": { base: "७", shift: "&" }, "8": { base: "८", shift: "*" },
    "9": { base: "९", shift: "(" }, "0": { base: "०", shift: ")" },
    "=": { base: null, shift: "त्र" },

    q: { base: "ृ", shift: "त्" }, w: { base: null, shift: "ऊ" },
    e: { base: "म", shift: "म्" }, r: { base: "त", shift: "त्र" },
    t: { base: "ज", shift: "ज्" }, y: { base: "ल", shift: "ल्" },
    u: { base: "न", shift: "न्" }, i: { base: "प", shift: "प्" },
    o: { base: "व", shift: "व्" }, p: { base: "च", shift: "च्" },
    "[": { base: "ख", shift: "क्ष" }, "]": { base: ",", shift: "ै" },

    a: { base: "ं", shift: "ष्" }, s: { base: "्", shift: "श" },
    d: { base: "क", shift: "क़" }, f: { base: "ि", shift: "ई", preBase: true },
    g: { base: "ह", shift: "ळ" }, h: { base: "ी", shift: "भ" },
    j: { base: "र", shift: "श्र" }, k: { base: "ा", shift: "ज्ञ" },
    l: { base: "स", shift: "स्" }, ";": { base: "य", shift: "रू" },
    "'": { base: "श", shift: "ठ" },

    z: { base: null, shift: "र्" }, x: { base: "ग", shift: "ग्" },
    c: { base: "ब", shift: "ब्" }, v: { base: "अ", shift: "ट" },
    b: { base: "इ", shift: "ठ" }, n: { base: "द", shift: "छ" },
    m: { base: "उ", shift: "ड" }, ",": { base: "ए", shift: "ढ" },
    ".": { base: "ण", shift: "झ" }, "/": { base: "ङ", shift: "घ" }
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

  "The best way to improve typing is through regular practice, honest self-review, and gradual increases in difficulty over time.",

  "Government offices are increasingly moving toward digital record keeping to reduce paperwork and improve transparency. Citizens now expect faster response times and easier access to services.",

  "Effective communication is the foundation of good customer service. Listening carefully and responding clearly prevents misunderstandings and builds trust between people.",

  "Software developers spend a significant part of their day writing, testing, and reviewing code. Clean and well documented code saves time for the entire team.",

  "Time management is an essential skill for anyone working in a busy office environment. Prioritizing tasks helps reduce stress and improves overall efficiency.",

  "Digital literacy has become just as important as traditional literacy in the modern workplace. Basic computer skills open doors to countless opportunities.",

  "A well organized desk and a clear mind often go hand in hand. Removing distractions allows the brain to focus fully on the task at hand.",

  "Public sector organizations are adopting new technologies to serve citizens more efficiently. Online portals reduce the need for repeated visits to government offices.",

  "Learning a new skill takes consistent effort over weeks and months rather than a single burst of motivation. Small daily habits compound into major results.",

  "Good typing skills reduce the time spent on data entry and documentation work. Accuracy matters more than raw speed when working with sensitive records.",

  "Teamwork often produces better results than individual effort alone. Sharing knowledge and supporting colleagues creates a stronger and more capable team.",

  "Reading regularly improves vocabulary, comprehension, and overall communication ability. A habit of reading even a few pages daily builds long term knowledge.",

  "Attention to detail is critical when handling official documents and citizen records. A single small error can lead to significant delays and confusion.",

  "Technology continues to reshape the way people work, communicate, and solve problems. Staying updated with new tools keeps professionals relevant in their field.",

  "Punctuality reflects discipline and respect for other people's time. Arriving prepared and on time creates a strong first impression in any setting.",

  "Clear and concise writing helps readers understand information quickly without confusion. Avoiding unnecessary words makes any document easier to read.",

  "Problem solving skills are valuable in almost every profession. Breaking a large problem into smaller steps makes it easier to find a solution.",

  "Customer trust is built slowly through consistent and honest service over time. A single negative experience can undo months of goodwill.",

  "Continuous learning keeps skills sharp in a rapidly changing job market. Professionals who invest in their own growth tend to advance faster.",

  "Data entry work requires patience, focus, and a steady rhythm. Rushing through records often introduces errors that take longer to fix later.",

  "A positive attitude at work can influence the mood of an entire team. Encouraging words and small gestures often make a big difference."
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

  "नियमित अभ्यास से उंगलियों की गति और शब्दों की पहचान दोनों में सुधार आता है।",

  "सरकारी कार्यालयों में अब डिजिटल रिकॉर्ड रखने पर अधिक जोर दिया जा रहा है। इससे कागजी कार्रवाई कम होती है और पारदर्शिता बढ़ती है।",

  "अच्छा संचार किसी भी सेवा का आधार होता है। ध्यान से सुनना और स्पष्ट रूप से जवाब देना गलतफहमी को रोकता है।",

  "समय प्रबंधन एक महत्वपूर्ण कौशल है जो कार्यस्थल पर तनाव कम करता है। प्राथमिकताएं तय करने से काम आसान हो जाता है।",

  "डिजिटल साक्षरता आज के समय में उतनी ही जरूरी है जितनी पारंपरिक शिक्षा। कंप्यूटर का बुनियादी ज्ञान नए अवसर खोलता है।",

  "साफ सुथरी मेज और शांत मन दोनों एक साथ बेहतर काम करने में मदद करते हैं। ध्यान भटकाने वाली चीजों को दूर रखना जरूरी है।",

  "सार्वजनिक क्षेत्र की संस्थाएं नई तकनीक अपनाकर नागरिकों को बेहतर सेवा दे रही हैं। ऑनलाइन पोर्टल बार-बार कार्यालय जाने की जरूरत को कम करते हैं।",

  "कोई भी नया कौशल सीखने में समय और निरंतर प्रयास लगता है। छोटी-छोटी आदतें मिलकर बड़े परिणाम देती हैं।",

  "अच्छी टाइपिंग क्षमता दस्तावेज़ीकरण के काम में समय बचाती है। संवेदनशील रिकॉर्ड के साथ काम करते समय गति से अधिक शुद्धता मायने रखती है।",

  "टीम वर्क अक्सर अकेले काम करने से बेहतर परिणाम देता है। ज्ञान साझा करना और सहकर्मियों की मदद करना टीम को मजबूत बनाता है।",

  "नियमित रूप से पढ़ने की आदत शब्दावली और समझ को बेहतर बनाती है। रोज कुछ पन्ने पढ़ना लंबे समय में बड़ा ज्ञान देता है।",

  "सरकारी दस्तावेजों को संभालते समय बारीकी पर ध्यान देना बहुत जरूरी है। एक छोटी सी गलती भी बड़ी देरी का कारण बन सकती है।",

  "तकनीक लगातार लोगों के काम करने और समस्याएं सुलझाने के तरीके को बदल रही है। नई तकनीक से जुड़े रहना करियर के लिए फायदेमंद है।",

  "समय की पाबंदी अनुशासन और दूसरों के समय के सम्मान को दर्शाती है। समय पर पहुंचना एक अच्छी छाप छोड़ता है।",

  "स्पष्ट और संक्षिप्त लेखन पाठकों को जानकारी जल्दी समझने में मदद करता है। अनावश्यक शब्दों से बचना दस्तावेज़ को आसान बनाता है।",

  "समस्या समाधान का कौशल लगभग हर पेशे में उपयोगी होता है। बड़ी समस्या को छोटे हिस्सों में बांटने से हल ढूंढना आसान हो जाता है।",

  "नागरिकों का भरोसा लगातार ईमानदार सेवा से धीरे-धीरे बनता है। एक बुरा अनुभव महीनों की मेहनत पर पानी फेर सकता है।",

  "लगातार सीखते रहना तेजी से बदलते कार्यक्षेत्र में कौशल को बनाए रखता है। जो लोग खुद पर निवेश करते हैं वे तेजी से आगे बढ़ते हैं।",

  "डेटा एंट्री के काम में धैर्य, ध्यान और एक स्थिर लय की जरूरत होती है। जल्दबाजी करने से गलतियां होती हैं जिन्हें बाद में सुधारना पड़ता है।",

  "कार्यस्थल पर सकारात्मक सोच पूरी टीम के माहौल को प्रभावित कर सकती है। अच्छे शब्द और छोटे इशारे भी बड़ा फर्क डालते हैं।",

  "अनुशासन और मेहनत के साथ किया गया काम हमेशा बेहतर परिणाम देता है। निरंतरता ही सफलता की सबसे बड़ी कुंजी है।"
];

  // Approximate words-per-minute a paragraph pool needs to sustain per
  // language, sized generously above typical human typing speed so a fast
  // typist never runs out of text before the timer ends. The renderer also
  // auto-extends the target text near the end as a safety net.
  const WPM_BUFFER = { english: 75, hindi: 55 };

  // Font choices per language. Kruti Dev is a legacy, non-Unicode font that
  // requires text to be encoded in its own ASCII key-map rather than
  // standard Unicode Devanagari, so it cannot be applied directly to this
  // Unicode text in a browser. The closest honest options are Unicode
  // Devanagari fonts styled to look close to common government/office fonts.
  const FONT_OPTIONS = {
    english: [
      { id: "sans", label: "Default (Sans)", family: "Inter, 'Segoe UI', Arial, sans-serif" },
      { id: "mono", label: "Monospace (Roboto Mono)", family: "'Roboto Mono', 'Courier New', monospace" },
      { id: "typewriter", label: "Typewriter (Courier Prime)", family: "'Courier Prime', 'Courier New', monospace" },
      { id: "modern", label: "Modern (Poppins)", family: "'Poppins', Inter, sans-serif" }
    ],
    hindi: [
      { id: "unicode", label: "Default (Unicode Devanagari)", family: "'Noto Sans Devanagari', 'Mangal', sans-serif" },
      { id: "kruti-style", label: "Kruti Dev style (Unicode alt.)", family: "'Baloo 2', 'Noto Sans Devanagari', sans-serif" },
      { id: "print", label: "Print style (Tiro Devanagari)", family: "'Tiro Devanagari Hindi', 'Noto Sans Devanagari', serif" }
    ]
  };

  const state = {
    direction: "hi-en",
    language: "english",
    active: false,
    duration: 300,
    remaining: 300,
    startedAt: 0,
    timerId: null,
    target: "",
    fontId: "sans",
    pool: [],
    poolCursor: 0,
    hindiInputMethod: "phonetic",
    kdPendingPreBase: ""
  };

  const $ = (id) => document.getElementById(id);

  const elements = {
    practicePanel: $("practicePanel"),
    practiceLanguage: $("practiceLanguage"),
    fontSelect: $("fontSelect"),
    hindiInputRow: $("hindiInputRow"),
    hindiInputMethod: $("hindiInputMethod"),
    krutiLegend: $("krutiLegend"),
    durationChips: $("durationChips"),
    textLengthHint: $("textLengthHint"),
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

  function shuffle(list) {
    const copy = list.slice();
    for (let i = copy.length - 1; i > 0; i -= 1) {
      const j = Math.floor(Math.random() * (i + 1));
      [copy[i], copy[j]] = [copy[j], copy[i]];
    }
    return copy;
  }

  function countWords(text) {
    return text.trim().split(/\s+/).filter(Boolean).length;
  }

  function requiredWordCount() {
    const minutes = state.duration / 60;
    const wpm = WPM_BUFFER[state.language] || 70;
    return Math.max(30, Math.ceil(minutes * wpm));
  }

  function refillPool() {
    state.pool = shuffle(getSamples());
    state.poolCursor = 0;
  }

  function nextParagraph() {
    if (state.poolCursor >= state.pool.length) {
      refillPool();
    }
    const paragraph = state.pool[state.poolCursor];
    state.poolCursor += 1;
    return paragraph;
  }

  function updateTextLengthHint() {
    if (!elements.textLengthHint) {
      return;
    }
    const words = countWords(state.target);
    const minutes = state.duration / 60;
    elements.textLengthHint.textContent = `~${words} words for ${minutes} min`;
  }

  // Builds a fresh paragraph long enough to comfortably outlast the chosen
  // duration for a fast typist, matching how real typing tests scale text
  // to time rather than handing out a single short paragraph.
  // function chooseTarget(fresh) {
  //   if (fresh || !state.pool.length) {
  //     refillPool();
  //   }
  //   const target = requiredWordCount();
  //   const parts = [];
  //   let words = 0;
  //   while (words < target) {
  //     const paragraph = nextParagraph();
  //     parts.push(paragraph);
  //     words += countWords(paragraph);
  //   }
  //   state.target = parts.join(" ");
  //   updateTextLengthHint();
  //   // Jump instantly to the top for a brand-new paragraph; smooth scrolling
  //   // only kicks in once typing is actually in progress (see scrollTargetToCurrent).
  //   elements.targetDisplay.style.scrollBehavior = "auto";
  //   renderTarget("");
  //   elements.targetDisplay.scrollTop = 0;
  //   void elements.targetDisplay.offsetHeight;
  //   elements.targetDisplay.style.scrollBehavior = "";
  // }

  function chooseTarget(fresh) {
  if (fresh || !state.pool.length) {
    refillPool();
  }

  const target = requiredWordCount();
  const parts = [];
  let words = 0;

  while (words < target) {
    const paragraph = nextParagraph();
    parts.push(paragraph);
    words += countWords(paragraph);
  }

  state.target = parts.join(" ");
  updateTextLengthHint();

  // Always start from the top
  elements.targetDisplay.scrollTop = 0;

  renderTarget("");
}

  // Called while the user is typing: if they are closing in on the end of
  // the current text before time runs out, silently append more so a fast
  // typist is never left with nothing to type.
  function extendTargetIfNeeded(typedLength) {
    if (!state.active) {
      return;
    }
    const remainingChars = Array.from(state.target).length - typedLength;
    if (remainingChars < 60) {
      state.target += " " + nextParagraph();
      updateTextLengthHint();
    }
  }

  function populateFontOptions() {
    const options = FONT_OPTIONS[state.language] || FONT_OPTIONS.english;
    elements.fontSelect.innerHTML = options.map((option) =>
      `<option value="${option.id}">${option.label}</option>`
    ).join("");
    const stillValid = options.some((option) => option.id === state.fontId);
    state.fontId = stillValid ? state.fontId : options[0].id;
    elements.fontSelect.value = state.fontId;
    applyFont();
  }

  function applyFont() {
    const options = FONT_OPTIONS[state.language] || FONT_OPTIONS.english;
    const chosen = options.find((option) => option.id === state.fontId) || options[0];
    elements.targetDisplay.style.fontFamily = chosen.family;
    elements.typingInput.style.fontFamily = chosen.family;
    if (elements.convertedPreviewText) {
      elements.convertedPreviewText.style.fontFamily = chosen.family;
    }
  }

  function escapeHtml(value) {
    return value.replace(/[&<>"']/g, (char) => ({
      "&": "&amp;", "<": "&lt;", ">": "&gt;", "\"": "&quot;", "'": "&#039;"
    }[char]));
  }

  function getTypedForScoring() {
    const raw = elements.typingInput.value;
    if (state.language === "hindi" && state.hindiInputMethod === "phonetic") {
      return transliterateEnglishToHindi(raw);
    }
    // Kruti Dev mode already builds real Devanagari text directly in the
    // textarea (see handleKrutiKeydown), so it needs no further conversion.
    return raw;
  }

  // Inserts a resolved Devanagari character/cluster at the cursor. When
  // `preBase` is true (the i-matra key), the character is held back and
  // combined with the very next consonant, in the correct Unicode order,
  // instead of being inserted immediately.
  function insertKrutiChar(char, preBase) {
    if (preBase) {
      state.kdPendingPreBase = char;
      return;
    }
    const el = elements.typingInput;
    const start = el.selectionStart;
    const end = el.selectionEnd;
    const text = state.kdPendingPreBase ? char + state.kdPendingPreBase : char;
    state.kdPendingPreBase = "";
    el.value = el.value.slice(0, start) + text + el.value.slice(end);
    const cursor = start + text.length;
    el.setSelectionRange(cursor, cursor);
    updateStats();
  }

  function flushKrutiPreBase() {
    if (!state.kdPendingPreBase) {
      return;
    }
    const el = elements.typingInput;
    const start = el.selectionStart;
    const end = el.selectionEnd;
    el.value = el.value.slice(0, start) + state.kdPendingPreBase + el.value.slice(end);
    const cursor = start + state.kdPendingPreBase.length;
    el.setSelectionRange(cursor, cursor);
    state.kdPendingPreBase = "";
    updateStats();
  }

  const KRUTI_PASSTHROUGH_KEYS = new Set([
    "Backspace", "Delete", "ArrowLeft", "ArrowRight", "ArrowUp", "ArrowDown",
    "Tab", "Home", "End", "Enter"
  ]);

  // Intercepts physical key presses and maps them through the Kruti Dev /
  // Remington key layout instead of letting the browser insert the raw
  // Latin letter, so the textarea always holds real, correctly ordered
  // Unicode Devanagari text.
  function handleKrutiKeydown(event) {
    if (state.language !== "hindi" || state.hindiInputMethod !== "krutidev") {
      return;
    }
    if (event.ctrlKey || event.metaKey || event.altKey) {
      return;
    }
    if (KRUTI_PASSTHROUGH_KEYS.has(event.key)) {
      if (event.key === "Backspace") {
        state.kdPendingPreBase = "";
      }
      return;
    }
    if (event.key === " ") {
      flushKrutiPreBase();
      return;
    }
    if (event.key.length !== 1) {
      return;
    }
    const mapping = KRUTI_KEYMAP[event.key.toLowerCase()];
    if (!mapping) {
      event.preventDefault();
      return;
    }
    event.preventDefault();
    const char = event.shiftKey ? mapping.shift : mapping.base;
    if (!char) {
      return;
    }
    insertKrutiChar(char, Boolean(mapping.preBase && !event.shiftKey));
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

  // Initial state: always show paragraph from the beginning
  if (!state.active && typedChars.length === 0) {
    elements.targetDisplay.scrollTop = 0;
    return;
  }

  scrollTargetToCurrent();
}


function scrollTargetToCurrent() {
  const container = elements.targetDisplay;
  const currentEl = container.querySelector(".current");

  if (!currentEl) {
    return;
  }

  const containerRect = container.getBoundingClientRect();
  const currentRect = currentEl.getBoundingClientRect();

  // Current line is already visible.
  // Don't unnecessarily move the paragraph.
  const topMargin = 25;
  const bottomMargin = 35;

  if (
    currentRect.top >= containerRect.top + topMargin &&
    currentRect.bottom <= containerRect.bottom - bottomMargin
  ) {
    return;
  }

  // Only scroll when current text moves outside the comfortable area.
  const targetScrollTop =
    container.scrollTop +
    (currentRect.top - containerRect.top) -
    topMargin;

  container.scrollTo({
    top: Math.max(0, targetScrollTop),
    behavior: "smooth"
  });
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
    extendTargetIfNeeded(Array.from(stats.typed).length);
    renderTarget(stats.typed);
    elements.wpmValue.textContent = String(stats.wpm);
    elements.accuracyValue.textContent = `${stats.accuracy}%`;
    elements.mistakeValue.textContent = String(stats.mistakes);

    if (state.language === "hindi" && state.hindiInputMethod === "phonetic") {
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

  function updateHindiInputUi() {
    const isHindi = state.language === "hindi";
    const isKruti = isHindi && state.hindiInputMethod === "krutidev";
    if (elements.hindiInputRow) {
      elements.hindiInputRow.classList.toggle("hidden", !isHindi);
    }
    elements.convertedPreview.classList.toggle("hidden", !isHindi || isKruti);
    if (elements.krutiLegend) {
      elements.krutiLegend.classList.toggle("hidden", !isKruti);
    }
    if (isKruti) {
      elements.typingInputLabel.textContent = "Type using Kruti Dev / Remington key positions";
      elements.typingInput.placeholder = "Press physical keys per the Kruti Dev layout (see legend below)";
    } else if (isHindi) {
      elements.typingInputLabel.textContent = "Type in roman Hindi; it is converted for scoring";
      elements.typingInput.placeholder = "Example: niyamit abhyas se typing ki gati...";
    } else {
      elements.typingInputLabel.textContent = "Type the text here";
      elements.typingInput.placeholder = "";
    }
  }

  function setHindiInputMethod(method) {
    state.hindiInputMethod = method;
    state.kdPendingPreBase = "";
    if (elements.hindiInputMethod) {
      elements.hindiInputMethod.value = method;
    }
    updateHindiInputUi();
    resetTest(false);
  }

  function setLanguage(language) {
    state.language = language;
    updateHindiInputUi();
    populateFontOptions();
    resetTest(false);
    chooseTarget(true);
  }

  function setDuration(seconds) {
    state.duration = seconds;
    if (elements.durationChips) {
      Array.from(elements.durationChips.children).forEach((chip) => {
        chip.classList.toggle("active", Number(chip.dataset.duration) === seconds);
      });
    }
    resetTest(true);
    chooseTarget(true);
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
  elements.fontSelect.addEventListener("change", () => {
    state.fontId = elements.fontSelect.value;
    applyFont();
  });
  if (elements.hindiInputMethod) {
    elements.hindiInputMethod.addEventListener("change", () => setHindiInputMethod(elements.hindiInputMethod.value));
  }
  if (elements.durationChips) {
    elements.durationChips.addEventListener("click", (event) => {
      const chip = event.target.closest(".duration-chip");
      if (!chip) {
        return;
      }
      setDuration(Number(chip.dataset.duration));
    });
  }
  elements.typingInput.addEventListener("keydown", handleKrutiKeydown);
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
  resetTest(true);
  renderHistory();
}());