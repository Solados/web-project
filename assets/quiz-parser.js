// Client-side CSV question loader with English/Arabic support
// Usage: fetchQuestions(source, count, lang) -> Promise<questions[]>
// Each question: { question: string, choices: string[], answer: string }

(function (window) {
  // small fallback set of questions in case of fetch/parse failure
const builtin = [
  { question: 'ما هي عاصمة المملكة العربية السعودية؟', choices: ['الرياض', 'جدة', 'مكة', 'الدمام'], answer: 'الرياض' },
  { question: 'ما هو اليوم الوطني السعودي؟', choices: ['23 سبتمبر', '1 يناير', '5 يونيو', '12 ديسمبر'], answer: '23 سبتمبر' },
  { question: 'ما هو اللباس التقليدي للرجال في السعودية؟', choices: ['الثوب', 'الكيمونو', 'الساري', 'البدلة'], answer: 'الثوب' },
  { question: 'ما هو اللباس التقليدي للنساء في السعودية؟', choices: ['العباءة', 'الكيمونو', 'الساري', 'الجلابية'], answer: 'العباءة' },
  { question: 'أي مدينة تُعرف بكونها أقدس مدينة في الإسلام؟', choices: ['مكة المكرمة', 'المدينة المنورة', 'الرياض', 'جدة'], answer: 'مكة المكرمة' },
  { question: 'أي مدينة تحتضن المسجد النبوي؟', choices: ['المدينة المنورة', 'مكة المكرمة', 'الرياض', 'الدمام'], answer: 'المدينة المنورة' },
  { question: 'ما هو اسم العملة السعودية؟', choices: ['الريال', 'الدينار', 'الجنيه', 'الدولار'], answer: 'الريال' },
  { question: 'ما هو الطبق الشعبي السعودي الشهير؟', choices: ['الكبسة', 'البيتزا', 'السوشي', 'البرياني'], answer: 'الكبسة' },
  { question: 'ما هو المشروب التقليدي الذي يُقدم مع التمر؟', choices: ['القهوة العربية', 'الشاي الأخضر', 'العصير', 'الحليب'], answer: 'القهوة العربية' },
  { question: 'ما هو لون العلم السعودي؟', choices: ['أخضر', 'أحمر', 'أزرق', 'أبيض'], answer: 'أخضر' },
  { question: 'ما هي العبارة المكتوبة على العلم السعودي؟', choices: ['لا إله إلا الله محمد رسول الله', 'الله أكبر', 'بسم الله الرحمن الرحيم', 'السلام عليكم'], answer: 'لا إله إلا الله محمد رسول الله' },
  { question: 'ما هو الحيوان الوطني في السعودية؟', choices: ['الجمل', 'الأسد', 'الصقر', 'الحصان'], answer: 'الصقر' },
  { question: 'ما هو أكبر ميناء بحري في السعودية؟', choices: ['ميناء جدة الإسلامي', 'ميناء الدمام', 'ميناء ينبع', 'ميناء جازان'], answer: 'ميناء جدة الإسلامي' },
  { question: 'أي منطقة تشتهر بالورود في السعودية؟', choices: ['الطائف', 'الرياض', 'القصيم', 'حائل'], answer: 'الطائف' },
  { question: 'ما هو اسم أكبر صحراء في السعودية؟', choices: ['الربع الخالي', 'صحراء النفود', 'صحراء سيناء', 'صحراء الكبرى'], answer: 'الربع الخالي' },
  { question: 'ما هو نوع الرقص الشعبي السعودي؟', choices: ['العرضة', 'التانغو', 'السامبا', 'الفلامنكو'], answer: 'العرضة' },
  { question: 'ما هو اسم أكبر جامعة في السعودية؟', choices: ['جامعة الملك سعود', 'جامعة الأزهر', 'جامعة القاهرة', 'جامعة دمشق'], answer: 'جامعة الملك سعود' },
  { question: 'ما هو اسم برج مشهور في الرياض؟', choices: ['برج المملكة', 'برج خليفة', 'برج إيفل', 'برج لندن'], answer: 'برج المملكة' },
  { question: 'أي مدينة سعودية تُعرف بعروس البحر الأحمر؟', choices: ['جدة', 'مكة', 'الدمام', 'المدينة'], answer: 'جدة' },
  { question: 'ما هو اسم المهرجان الثقافي الذي يقام في الجنادرية؟', choices: ['مهرجان الجنادرية', 'مهرجان الطائف', 'مهرجان الرياض', 'مهرجان جدة'], answer: 'مهرجان الجنادرية' }
];

  function shuffle(arr) {
    for (let i = arr.length - 1; i > 0; i--) {
      const j = Math.floor(Math.random() * (i + 1));
      [arr[i], arr[j]] = [arr[j], arr[i]];
    }
  }

  // Robust CSV line parser — handles quoted fields with commas
  function parseCSVLine(line) {
    const out = [];
    let cur = '';
    let inQuote = false;
    for (let i = 0; i < line.length; i++) {
      const ch = line[i];
      if (ch === '"') {
        if (inQuote && line[i + 1] === '"') { // escaped quote
          cur += '"';
          i++; // skip
        } else {
          inQuote = !inQuote;
        }
        continue;
      }
      if (ch === ',' && !inQuote) {
        out.push(cur);
        cur = '';
        continue;
      }
      cur += ch;
    }
    out.push(cur);
    return out.map(s => s.trim());
  }

  async function fetchText(path) {
    const r = await fetch(path);
    if (!r.ok) throw new Error('Fetch failed: ' + r.status);
    return await r.text();
  }

  // parse a CSV and return {header:[], rows:[[]]}
  // Robust parser: supports quoted fields that contain commas and newlines.
  function parseCSV(text) {
    if (!text || typeof text !== 'string') return { header: [], rows: [] };
    const rows = [];
    let field = '';
    let row = [];
    let inQuote = false;
    for (let i = 0; i < text.length; i++) {
      const ch = text[i];
      const next = text[i + 1];
      if (ch === '"') {
        if (inQuote && next === '"') {
          // escaped quote
          field += '"';
          i++; // skip escaped quote
          continue;
        }
        inQuote = !inQuote;
        continue;
      }
      if (!inQuote && ch === ',') {
        row.push(field);
        field = '';
        continue;
      }
      if (!inQuote && (ch === '\n' || ch === '\r')) {
        // handle CRLF
        if (ch === '\r' && text[i + 1] === '\n') { i++; }
        row.push(field);
        rows.push(row);
        row = [];
        field = '';
        continue;
      }
      field += ch;
    }
    // push last field/row
    // if there is any leftover (including possible final newline-less row)
    if (inQuote) {
      // unterminated quote — try to salvage
      // treat remaining field as-is
    }
    if (field !== '' || row.length > 0) {
      row.push(field);
      rows.push(row);
    }

    if (rows.length === 0) return { header: [], rows: [] };
    // normalize header and rows (trim but preserve internal spacing)
    const header = rows[0].map(s => (s || '').toString().trim());
    const dataRows = rows.slice(1).map(r => r.map(s => (s || '').toString().trim()));
    // filter out completely empty rows
    const filtered = dataRows.filter(r => r.some(c => c !== ''));
    return { header, rows: filtered };
  }

  // normalize header name -> index map (lowercase trimmed)
  function headerMap(header) {
    const map = {};
    header.forEach((h, idx) => {
      map[h.trim().toLowerCase()] = idx;
    });
    return map;
  }

  // Build questions when CSV has Question/Answer columns (GENERAL.csv style)
  function buildFromQA(hmap, rows, count) {
    // support variations of the 'type' header (e.g., 'question type')
    const qIdx = hmap['question'];
    const ansIdx = hmap['answer'];
    const choicesIdx = hmap['choices'];
    const questions = [];

    // find possible type header index
    let typeIdx = undefined;
    for (const k of ['type', 'question type', 'question_type', 'qtype']) {
      if (k in hmap) { typeIdx = hmap[k]; break; }
    }

    // randomize row order so selecting "all" samples mixed types
    shuffle(rows);
    for (const r of rows) {
      if (qIdx === undefined) break;
      if (!r[qIdx]) continue;
      const qText = r[qIdx];
      let correct = ansIdx !== undefined ? (r[ansIdx] || '').trim() : '';
      let choices = [];

      // If choices column exists and is not '–', parse it (use database choices as-is)
      if (choicesIdx !== undefined && r[choicesIdx] && r[choicesIdx] !== '–') {
        const raw = r[choicesIdx];
        const parts = raw.split(/\s*[;|\|]\s*|\s*A\.\s*|\s*B\.\s*|\s*C\.\s*|\s*D\.\s*/).map(s => s.trim()).filter(Boolean);
        if (parts.length >= 2) choices = parts;
      }

      // ensure unique and at most 4 when choices were provided
      if (choices.length > 0) {
        choices = Array.from(new Set(choices)).slice(0, 4);
        // If the correct answer is expressed as letters (e.g., "A & C", "A,C", "A and C"), map
        // those letters to the actual choice texts so we don't inject the raw "A & C" string into choices.
        if (correct && !choices.includes(correct)) {
          const letterMatches = correct.match(/[A-D]/gi);
          if (letterMatches && letterMatches.length > 0) {
            const letterMap = { A:0, B:1, C:2, D:3 };
            const resolved = letterMatches.map(l => {
              const idx = letterMap[(l||'').toUpperCase()];
              return (typeof idx === 'number' && choices[idx]) ? choices[idx] : null;
            }).filter(Boolean);
            const uniqueResolved = Array.from(new Set(resolved));
            if (uniqueResolved.length > 0) {
              // set the correct variable to the resolved texts joined by a separator
              correct = uniqueResolved.join(' / ');
            }
          }
          // ensure the (possibly resolved) correct answer is present in choices
          if (!choices.includes(correct)) {
            choices[0] = correct;
          }
        }
      }

      // Build question object. If no choices were provided in the CSV, return as open-ended (choices: []).
      const qObj = { question: qText, choices: Array.isArray(choices) ? choices.slice() : [], answer: correct };
      if (typeIdx !== undefined) qObj.type = (r[typeIdx] || '').trim();
      questions.push(qObj);
      if (questions.length >= count) break;
    }
    return questions.slice(0, count);
  }

  // Extract MCQ from embedded Arabic question block (Words/Phrases/Proverbs.csv style)
  function extractMCQFromBlock(blockText) {
    if (!blockText || typeof blockText !== 'string') return null;
    const lines = blockText.split(/\r?\n/);
    let question = '';
    let choices = [];
    let answer = null;

    // find question line (starts with السؤال: or المهمة:)
    for (const line of lines) {
      if (/السؤال\s*:/i.test(line)) {
        question = line.replace(/.*السؤال\s*:\s*/i, '').trim();
        break;
      }
    }
    if (!question) {
      for (const line of lines) {
        if (/المهمة\s*:/i.test(line)) {
          question = line.replace(/.*المهمة\s*:\s*/i, '').trim();
          break;
        }
      }
    }

    // find choices (Arabic أ ب ج د or Latin A B C D with ) or . markers)
    for (const line of lines) {
      let match;
      // Arabic markers: أ ب ج د (with optional ) or ) or .)
      if ((match = line.match(/^[\s\-]*([أابجد])\s*[)\.\-]\s*(.+)$/i))) {
        choices.push(match[2].trim());
        continue;
      }
      // Latin markers A B C D (A) or A. or A - )
      if ((match = line.match(/^[\s\-]*([A-D])\s*[)\.\-]\s*(.+)$/i))) {
        choices.push(match[2].trim());
        continue;
      }
      // Some files list 'الخيارات:' followed by indented lines without markers; capture lines that look like option lines (start with Arabic letter then ) without space)
      if ((match = line.match(/^[\s]*[A-Za-zأبجد][\)\.\-]?\s*(.+)$/i))) {
        // only add if it looks like a short option (heuristic)
        const txt = match[1].trim();
        if (txt && txt.length < 200) choices.push(txt);
      }
    }

    // find correct answer
    for (const line of lines) {
      if (/الإجابة\s*الصحيحة\s*:/i.test(line)) {
        // try Arabic letter, then Latin letter, then text
        const m = line.match(/الإجابة\s*الصحيحة\s*:\s*([أبجد])/i) || line.match(/الإجابة\s*الصحيحة\s*:\s*([A-D])/i) || line.match(/الإجابة\s*الصحيحة\s*:\s*(.+)$/i);
        if (m) {
          const val = (m[1] || '').toString().trim();
          // map Arabic letters
          const letterMap = { 'أ': 0, 'ا': 0, 'ب': 1, 'ج': 2, 'د': 3 };
          if (val && (val in letterMap)) {
            const idx = letterMap[val];
            if (choices[idx]) answer = choices[idx];
          } else if (/^[A-D]$/i.test(val)) {
            const idx = ['A','B','C','D'].indexOf(val.toUpperCase());
            if (idx !== -1 && choices[idx]) answer = choices[idx];
          } else if (m[1]) {
            // text answer — m[1] already contains the text in this branch
            answer = m[1].trim();
            if (!choices.includes(answer)) {
              choices[0] = answer; // replace first if not in list
            }
          }
        }
        break;
      }
    }

    if (question && choices.length >= 2) {
      return { question, choices: choices.slice(0, 4), answer: answer || choices[0] };
    }
    return null;
  }

  // Build from Terms+Meanings (Words.csv style) with MCQ extraction
  function buildFromTerms(hmap, rows, count, lang) {
    const termIdx = hmap['term'];
    const typeIdx = hmap['type'];
    // detect possible meaning column names
    const meaningKeys = ['meaning_of_term', 'meaning', 'translation', 'definition'];
    let meaningIdx = undefined;
    for (const k of meaningKeys) {
      if (k in hmap) { meaningIdx = hmap[k]; break; }
    }
    // fallback to second column
    if (meaningIdx === undefined) {
      const keys = Object.keys(hmap);
      if (keys.length >= 2) meaningIdx = 1;
    }

    const hasChoicesColumn = Object.keys(hmap).some(k => ['choices', 'choice', 'options'].includes(k));
    const questions = [];
    const questionCols = Object.keys(hmap).filter(k => /question/i.test(k) || /سؤال/i.test(k));

    // try to extract MCQ from question columns first
    for (const row of rows) {
      if (questions.length >= count) break;
      for (const qCol of questionCols) {
        if (questions.length >= count) break;
        const qIdx = hmap[qCol];
        if (qIdx === undefined) continue;
        const blockText = row[qIdx];
        const mcq = extractMCQFromBlock(blockText);
        if (mcq) {
          if (typeIdx !== undefined) mcq.type = (row[typeIdx] || '').trim();
          questions.push(mcq);
        }
      }
    }

    // fallback: if not enough, build from term+meaning
    if (questions.length < count) {
      const items = rows.map(r => ({ term: r[termIdx] || '', meaning: (meaningIdx !== undefined ? r[meaningIdx] : '') || '' })).filter(x => x.term && x.meaning);
      if (items.length > 0) {
          shuffle(items);
          const poolMeanings = items.map(i => i.meaning);
          for (const it of items) {
            if (questions.length >= count) break;
            const correct = it.meaning;
            const pool = poolMeanings.filter(m => m && m !== correct);
            shuffle(pool);
            // If the source CSV does not include an explicit choices/options column
            // do not synthesize/pad extra options — leave as open-ended (no choices).
            let qObj;
            const qText = (lang === 'en') ? `What is the meaning of "${it.term}"?` : `ما معنى "${it.term}"؟`;
            if (!hasChoicesColumn) {
              qObj = { question: qText, choices: [], answer: correct };
            } else {
              const distractors = pool.slice(0, 3);
              let choices = [correct].concat(distractors).slice(0, 4);
              shuffle(choices);
              qObj = { question: qText, choices, answer: correct };
            }
            if (typeIdx !== undefined) qObj.type = (it.type || '').trim();
            questions.push(qObj);
          }
        }
    }

    return questions.slice(0, count);
  }

  // Public API
  // fetchQuestions(source, count, lang, type, category)
  async function fetchQuestions(source, count, lang, type, category) {
    count = Number(count) || 5;
    lang = String(lang || 'ar');
    // Determine the correct data directory prefix depending on page location.
    // If the page is served from the `quiz/` folder, use `../data/`, otherwise `data/`.
    const _pathname = (window.location && window.location.pathname) ? window.location.pathname : '';
    const dataPrefix = (_pathname.split && _pathname.split('/').indexOf('quiz') !== -1) ? '../data/' : 'data/';
    let file = dataPrefix + 'Words.csv';
    if (source && source !== 'Words') file = `${dataPrefix}${source}.csv`;

    try {
      const txt = await fetchText(file);
      const parsed = parseCSV(txt);
      if (!parsed.header || parsed.header.length === 0) throw new Error('No header');
      const hmap = headerMap(parsed.header);

      const hasChoicesColumn = Object.keys(hmap).some(k => ['choices', 'choice', 'options'].includes(k));

      // filter rows client-side based on type/category if possible
      let filteredRows = parsed.rows.slice();

      // helper to find a matching header key from candidates
      function findHeaderKey(candidates) {
        for (const k of candidates) {
          if (k in hmap) return k;
        }
        return null;
      }

      // apply category filter
      // apply TYPE filter (Question Type)
if (type && String(type).toLowerCase() !== 'all') {

  function canonType(s) {
    const t = String(s || '').toLowerCase().trim();
    if (t.includes('multiple')) return 'multi';
    if (t.includes('one')) return 'one';
    if (t.includes('open')) return 'open';
    if (t.includes('fill')) return 'open';
    return t;
  }

  const requested = canonType(type);

  // Question Type
  const typeKeys = ['question type', 'type', 'question_type', 'questiontype', 'qtype', 'question-type'];
  const found = findHeaderKey(typeKeys);

  if (found !== null) {
    // Filter from the same column
    const idx = hmap[found];
    filteredRows = filteredRows.filter(r => canonType(r[idx]) === requested);
  } else {
    // fallback heuristic only if no type column
    const choicesKeys = ['choices', 'choice', 'options'];
    const choicesKey = findHeaderKey(choicesKeys);
    if (choicesKey !== null) {
      const cidx = hmap[choicesKey];

      if (requested === 'open') {
        filteredRows = filteredRows.filter(r => !r[cidx] || String(r[cidx]).trim() === '' || String(r[cidx]).trim() === '–');
      } else {
        // one or multi: choices must be available
        filteredRows = filteredRows.filter(r => r[cidx] && String(r[cidx]).trim() !== '' && String(r[cidx]).trim() !== '–');
      }
    }
  }
}


      if (category && String(category).toLowerCase() !== 'all') {
        const catKeys = ['category', 'categories', 'topic', 'tag', 'tags', 'category_name'];
        const found = findHeaderKey(catKeys);
        if (found !== null) {
          const idx = hmap[found];
          filteredRows = filteredRows.filter(r => {
            const val = (r[idx] || '') + '';
            return val.toLowerCase().indexOf(String(category).toLowerCase()) !== -1;
          });
        }
      }

      // choose builder using filtered rows
      if ('question' in hmap && 'answer' in hmap) {
        // data already in QA format (likely English)
        return buildFromQA(hmap, filteredRows, count);
      }

      if ('term' in hmap) {
        return buildFromTerms(hmap, filteredRows, count, lang);
      }

      // generic fallback: try treat first col as question and second as answer
      if (parsed.rows.length > 0) {
        const questions = [];
        const pool = parsed.rows.map(r => r[1] || '').filter(Boolean);
        const typeIdx = hmap['type'];
        shuffle(pool);
        for (let i = 0; i < Math.min(count, parsed.rows.length); i++) {
          const row = parsed.rows[i];
          const q = row[0] || ('Question ' + (i + 1));
          const a = row[1] || pool[i] || 'Answer';
          let qObj;
          if (!hasChoicesColumn) {
            qObj = { question: (lang === 'en' ? q : q), choices: [], answer: a };
          } else {
            const distract = pool.filter(x => x !== a).slice(0, 3);
            let choices = [a].concat(distract).slice(0, 4);
            shuffle(choices);
            qObj = { question: (lang === 'en' ? q : q), choices, answer: a };
          }
          if (typeIdx !== undefined) qObj.type = (row[typeIdx] || '').trim();
          questions.push(qObj);
        }
        return questions;
      }
      throw new Error('Unable to parse CSV');
    } catch (err) {
      console.warn('fetchQuestions error:', err);
      // return a copy of builtin
      const out = builtin.slice(0, Math.min(count, builtin.length)).map(q => ({ question: q.question, choices: q.choices.slice(), answer: q.answer }));
      return out;
    }
  }

  // fetchQuestionTypes(source) -> Promise<string[]>
  // Returns distinct Question Type strings present in the CSV, or inferred types when missing.
  async function fetchQuestionTypes(source) {
    const _p2 = (window.location && window.location.pathname) ? window.location.pathname : '';
    const dataPrefix2 = (_p2.split && _p2.split('/').indexOf('quiz') !== -1) ? '../data/' : 'data/';
    let file = dataPrefix2 + 'Words.csv';
    if (source && source !== 'Words') file = `${dataPrefix2}${source}.csv`;
    try {
      const txt = await fetchText(file);
      const parsed = parseCSV(txt);
      const hmap = headerMap(parsed.header);
      const out = new Set();

      // look for explicit type headers
      const typeKeys = ['type', 'question type', 'question_type', 'qtype', 'questiontype'];
      let foundTypeKey = null;
      for (const k of typeKeys) if (k in hmap) { foundTypeKey = k; break; }
      if (foundTypeKey !== null) {
        const idx = hmap[foundTypeKey];
        for (const r of parsed.rows) {
          const v = (r[idx] || '').trim();
          if (v) out.add(v);
        }
        return Array.from(out).sort();
      }

      // fallback: infer types from choices column
      const choicesKeys = ['choices', 'choice', 'options'];
      let choicesKey = null;
      for (const k of choicesKeys) if (k in hmap) { choicesKey = k; break; }
      let hasMCQ = false, hasMulti = false, hasOpen = false;
      const ansKey = ('answer' in hmap) ? hmap['answer'] : null;
      for (const r of parsed.rows) {
        if (choicesKey !== null) {
          const c = (r[hmap[choicesKey]] || '').trim();
          if (c && c !== '–') {
            hasMCQ = true;
            // detect multiple-correct by answer pattern (e.g., 'A and B', 'A, B', 'A and C')
            if (ansKey !== null) {
              const a = (r[ansKey] || '').toString();
              if (/\band\b|,|and|\bs?and\b|\bوا\b/i.test(a) || /[A-D]\s*(and|,)/i.test(a)) {
                hasMulti = true;
              }
            }
          } else {
            hasOpen = true;
          }
        } else {
          // no choices column at all — assume open-ended
          hasOpen = true;
        }
      }
      if (hasOpen) out.add('Open-ended');
      if (hasMCQ) out.add('MCQ (one correct)');
      if (hasMulti) out.add('MCQ (multiple correct)');
      return Array.from(out).sort();
    } catch (e) {
      console.warn('fetchQuestionTypes error:', e);
      return [];
    }
  }

  // fetchCategories(source) -> Promise<string[]>
  // Reads Category column and returns distinct categories for the given CSV file.
  async function fetchCategories(source) {
    const _pathname = (window.location && window.location.pathname) ? window.location.pathname : '';
    const dataPrefix = (_pathname.split && _pathname.split('/').indexOf('quiz') !== -1) ? '../data/' : 'data/';

    let file = dataPrefix + 'Words.csv';
    if (source && source !== 'Words') file = `${dataPrefix}${source}.csv`;

    try {
      const txt = await fetchText(file);
      const parsed = parseCSV(txt);
      if (!parsed.header || parsed.header.length === 0) return [];

      const hmap = headerMap(parsed.header);

      // Because your column name is "Category", headerMap will map it to 'category'
      if (!('category' in hmap)) return [];

      const idx = hmap['category'];

      const seen = new Map(); // key lower => original
      for (const r of parsed.rows) {
        const raw = String(r[idx] || '').trim();
        if (!raw) continue;

        // If a cell contains more than one category separated by commas
        raw.split(/[,;|/]+/).forEach(part => {
          const v = String(part || '').trim();
          if (!v) return;
          const key = v.toLowerCase();
          if (!seen.has(key)) seen.set(key, v);
        });
      }

      return Array.from(seen.values()).sort((a, b) => a.localeCompare(b));
    } catch (e) {
      console.warn('fetchCategories error:', e);
      return [];
    }
  }

  window.fetchQuestions = fetchQuestions;
  window.fetchQuestionTypes = fetchQuestionTypes;
  window.fetchCategories = fetchCategories;
  window.allQuestions = builtin.slice();

})(window);

