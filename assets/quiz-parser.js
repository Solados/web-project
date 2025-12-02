// Client-side CSV question loader with English/Arabic support
// Usage: fetchQuestions(source, count, lang) -> Promise<questions[]>
// Each question: { question: string, choices: string[], answer: string }

(function (window) {
  // small fallback set
  const builtin = [
    { question: 'ما معنى "كتاب"؟', choices: ['Book', 'Table', 'Window', 'Door'], answer: 'Book' },
    { question: 'ما معنى "ماء"؟', choices: ['Water', 'Fire', 'Earth', 'Air'], answer: 'Water' },
    { question: 'ما معنى "بيت"؟', choices: ['House', 'Car', 'Street', 'Food'], answer: 'House' }
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
  function parseCSV(text) {
    const lines = text.split(/\r?\n/).filter(l => l.trim() !== '');
    if (lines.length === 0) return { header: [], rows: [] };
    const header = parseCSVLine(lines[0]);
    const rows = [];
    for (let i = 1; i < lines.length; i++) {
      try {
        rows.push(parseCSVLine(lines[i]));
      } catch (e) {
        // skip malformed
      }
    }
    return { header, rows };
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
    const qIdx = hmap['question'];
    const ansIdx = hmap['answer'];
    const choicesIdx = hmap['choices'];
    const typeIdx = hmap['type'];
    const questions = [];

    for (const r of rows) {
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

      // If choices column is '–' or empty (no pre-defined choices in database), skip this row
      // Do not generate distractors—only use database-provided content
      if (choices.length === 0) {
        continue; // Skip rows without explicit choices
      }

      // ensure unique and at most 4
      choices = Array.from(new Set(choices)).slice(0, 4);
      if (!choices.includes(correct)) {
        choices[0] = correct; // ensure correct present
      }
      
      // only add if we have at least 2 choices
      if (choices.length >= 2) {
        shuffle(choices);
        const qObj = { question: qText, choices, answer: correct };
        if (typeIdx !== undefined) qObj.type = (rows[0] && rows[0][typeIdx]) ? (r[typeIdx] || '').trim() : (r[typeIdx] || '');
        // include any type value present on this row
        if (typeIdx !== undefined) qObj.type = (r[typeIdx] || '').trim();
        questions.push(qObj);
        if (questions.length >= count) break;
      }
    }
    return questions;
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

    // find choices (lines with أ) ب) ج) د) or A) B) C) D))
    for (const line of lines) {
      let match;
      if ((match = line.match(/^[أ][\))]?\s*(.+)$/))) {
        choices.push(match[1].trim());
      } else if ((match = line.match(/^[ب][\))]?\s*(.+)$/))) {
        choices.push(match[1].trim());
      } else if ((match = line.match(/^[ج][\))]?\s*(.+)$/))) {
        choices.push(match[1].trim());
      } else if ((match = line.match(/^[د][\))]?\s*(.+)$/))) {
        choices.push(match[1].trim());
      }
    }

    // find correct answer
    for (const line of lines) {
      if (/الإجابة\s*الصحيحة\s*:/i.test(line)) {
        const match = line.match(/الإجابة\s*الصحيحة\s*:\s*([أبجد])|الإجابة\s*الصحيحة\s*:\s*([^,]+)/i);
        if (match) {
          if (match[1]) {
            // letter answer (أ = 0, ب = 1, ج = 2, د = 3)
            const letterMap = { 'أ': 0, 'ب': 1, 'ج': 2, 'د': 3 };
            const idx = letterMap[match[1]];
            if (idx !== undefined && choices[idx]) answer = choices[idx];
          } else if (match[2]) {
            // text answer
            answer = match[2].trim();
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
          const distractors = pool.slice(0, 3);
          let choices = [correct].concat(distractors).slice(0, 4);
          shuffle(choices);
          const qText = (lang === 'en') ? `What is the meaning of "${it.term}"?` : `ما معنى "${it.term}"؟`;
          const qObj = { question: qText, choices, answer: correct };
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
    let file = 'data/Words.csv';
    if (source && source !== 'Words') file = `data/${source}.csv`;

    try {
      const txt = await fetchText(file);
      const parsed = parseCSV(txt);
      if (!parsed.header || parsed.header.length === 0) throw new Error('No header');
      const hmap = headerMap(parsed.header);

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
      if (type && String(type).toLowerCase() !== 'all') {
        const typeKeys = ['type', 'question_type', 'qtype', 'questiontype'];
        const found = findHeaderKey(typeKeys);
        if (found !== null) {
          const idx = hmap[found];
          filteredRows = filteredRows.filter(r => {
            const val = (r[idx] || '') + '';
            return val.toLowerCase().indexOf(String(type).toLowerCase()) !== -1;
          });
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
          const distract = pool.filter(x => x !== a).slice(0, 3);
          let choices = [a].concat(distract).slice(0, 4);
          shuffle(choices);
          const qObj = { question: (lang === 'en' ? q : q), choices, answer: a };
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

  window.fetchQuestions = fetchQuestions;
  window.allQuestions = builtin.slice();

})(window);
