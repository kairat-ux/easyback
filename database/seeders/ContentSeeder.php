<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Lesson;
use App\Models\Exercise;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $teacher = User::where('email', 'teacher@englisheasy.com')->first();
        if (!$teacher) {
            $teacher = User::where('role', 'teacher')->first();
        }

        $lessons = [

            // ── GRAMMAR ──────────────────────────────────────────────────
            [
                'title' => 'Present Simple vs Present Continuous',
                'description' => 'Master the two most common present tenses in English.',
                'content' => "## Present Simple\nUsed for habits, facts, and routines.\n- I **work** every day.\n- The sun **rises** in the east.\n- She **doesn't like** coffee.\n\n## Present Continuous\nUsed for actions happening right now or temporary situations.\n- I **am working** right now.\n- They **are staying** at a hotel this week.\n- He **isn't sleeping** — he's reading.\n\n## Key Time Expressions\nPresent Simple: always, usually, often, never, every day\nPresent Continuous: now, at the moment, currently, today, this week\n\n## State Verbs (never continuous!)\nknow, believe, love, hate, want, need, understand, remember",
                'vocabulary' => [
                    ['word' => 'routine', 'translation' => 'распорядок дня'],
                    ['word' => 'habit', 'translation' => 'привычка'],
                    ['word' => 'temporary', 'translation' => 'временный'],
                    ['word' => 'permanent', 'translation' => 'постоянный'],
                    ['word' => 'state verb', 'translation' => 'глагол состояния'],
                ],
            ],
            [
                'title' => 'Past Simple vs Past Continuous',
                'description' => 'Learn how to talk about past events and interrupted actions.',
                'content' => "## Past Simple\nUsed for completed actions at a specific time in the past.\n- I **visited** Paris last year.\n- She **didn't call** me yesterday.\n- **Did** you **watch** the game?\n\n## Past Continuous\nUsed for actions in progress at a specific moment in the past.\n- I **was sleeping** when you called.\n- They **were playing** football all afternoon.\n\n## Combining Both\nWhen one action interrupts another:\n- I **was walking** home when it **started** to rain.\n- She **was cooking** dinner when the phone **rang**.\n\n## Irregular Verbs\ngo→went, see→saw, eat→ate, drink→drank, write→wrote",
                'vocabulary' => [
                    ['word' => 'interrupt', 'translation' => 'прервать'],
                    ['word' => 'meanwhile', 'translation' => 'тем временем'],
                    ['word' => 'suddenly', 'translation' => 'внезапно'],
                    ['word' => 'irregular verb', 'translation' => 'неправильный глагол'],
                    ['word' => 'completed action', 'translation' => 'завершённое действие'],
                ],
            ],
            [
                'title' => 'Future Tenses: will, going to, Present Continuous',
                'description' => 'Three ways to talk about the future — and when to use each.',
                'content' => "## Will\nUsed for spontaneous decisions, predictions, offers, promises.\n- I **will help** you with that. (spontaneous)\n- It **will rain** tomorrow. (prediction based on belief)\n- I **will always love** you. (promise)\n\n## Going To\nUsed for plans and predictions based on evidence.\n- I **am going to visit** my parents next weekend. (plan)\n- Look at those clouds — it **is going to rain**. (evidence)\n\n## Present Continuous for Future\nUsed for fixed arrangements with other people.\n- I **am meeting** John at 7pm tomorrow.\n- We **are flying** to London on Friday.\n\n## Quick Guide\n- Spontaneous → **will**\n- Plan/intention → **going to**\n- Arrangement → **Present Continuous**",
                'vocabulary' => [
                    ['word' => 'spontaneous', 'translation' => 'спонтанный'],
                    ['word' => 'intention', 'translation' => 'намерение'],
                    ['word' => 'arrangement', 'translation' => 'договорённость'],
                    ['word' => 'prediction', 'translation' => 'предсказание'],
                    ['word' => 'evidence', 'translation' => 'доказательство'],
                ],
            ],
            [
                'title' => 'Articles: A, An, The',
                'description' => 'One of the trickiest topics for learners — master articles once and for all.',
                'content' => "## Indefinite Articles: A / An\nUse **a** before consonant sounds, **an** before vowel sounds.\n- a **c**at, a **u**niversity (\"yu\" sound)\n- an **a**pple, an **h**our (silent h)\n\nUse a/an when:\n- Mentioning something for the first time\n- Talking about one of many\n- Saying what something is\n\n## Definite Article: The\nUse **the** when:\n- Both speaker and listener know which one\n- Mentioning something for the second time\n- There is only one (the sun, the moon, the internet)\n- With superlatives (the best, the tallest)\n- With countries that are unions/republics (the USA, the UK)\n\n## No Article (Zero Article)\n- Languages: I speak **English**\n- Meals: I had **lunch**\n- Most countries: I visited **France**\n- Sports: I play **football**",
                'vocabulary' => [
                    ['word' => 'indefinite', 'translation' => 'неопределённый'],
                    ['word' => 'definite', 'translation' => 'определённый'],
                    ['word' => 'consonant', 'translation' => 'согласная'],
                    ['word' => 'vowel', 'translation' => 'гласная'],
                    ['word' => 'superlative', 'translation' => 'превосходная степень'],
                ],
            ],
            [
                'title' => 'Conditionals: Zero, First, Second, Third',
                'description' => 'If-clauses from real situations to hypothetical and impossible scenarios.',
                'content' => "## Zero Conditional — General Truths\nIf + present simple, present simple\n- **If** you heat water to 100°C, **it boils**.\n- **If** I eat too much, **I feel** sick.\n\n## First Conditional — Real Future Possibility\nIf + present simple, will + infinitive\n- **If** it rains, I **will** stay home.\n- I **will** call you **if** I need help.\n\n## Second Conditional — Hypothetical Present/Future\nIf + past simple, would + infinitive\n- **If** I had a million dollars, I **would** travel the world.\n- **If** she **studied** harder, she **would** pass.\n\n## Third Conditional — Impossible Past\nIf + past perfect, would have + past participle\n- **If** I had studied, I **would have** passed the exam.\n- She **wouldn't have** been late **if** she had set an alarm.\n\n## Mixed Conditionals\nMix 2nd and 3rd for present result of past action:\n- If I had slept earlier (3rd), I wouldn't be tired now (2nd).",
                'vocabulary' => [
                    ['word' => 'conditional', 'translation' => 'условное предложение'],
                    ['word' => 'hypothetical', 'translation' => 'гипотетический'],
                    ['word' => 'imaginary', 'translation' => 'воображаемый'],
                    ['word' => 'regret', 'translation' => 'сожаление'],
                    ['word' => 'consequence', 'translation' => 'последствие'],
                ],
            ],
            [
                'title' => 'Modal Verbs: Can, Could, Should, Must, May, Might',
                'description' => 'Express ability, possibility, obligation, and permission.',
                'content' => "## Ability\n- **Can** / **Could**: I **can** swim. I **could** swim when I was 5.\n\n## Permission\n- **Can** / **May**: **Can** I open the window? (informal) / **May** I leave? (formal)\n\n## Possibility\n- **May** / **Might**: It **may** rain. He **might** come later. (50/50 or less)\n- **Could**: She **could** be at home. (possible)\n\n## Obligation\n- **Must**: You **must** wear a seatbelt. (strong, often rules)\n- **Have to**: I **have to** work tomorrow. (external obligation)\n- **Should** / **Ought to**: You **should** exercise more. (advice)\n\n## Prohibition\n- **Mustn't**: You **mustn't** smoke here. (forbidden)\n- **Don't have to**: You **don't have to** come. (not necessary)\n\n## Deduction\n- **Must**: She's not answering — she **must** be sleeping.\n- **Can't**: That **can't** be true!\n- **Might**: He **might** have missed the bus.",
                'vocabulary' => [
                    ['word' => 'obligation', 'translation' => 'обязательство'],
                    ['word' => 'permission', 'translation' => 'разрешение'],
                    ['word' => 'prohibition', 'translation' => 'запрет'],
                    ['word' => 'deduction', 'translation' => 'вывод'],
                    ['word' => 'necessity', 'translation' => 'необходимость'],
                ],
            ],

            // ── VOCABULARY ───────────────────────────────────────────────
            [
                'title' => 'Travel & Tourism Vocabulary',
                'description' => 'Essential words for booking trips, airports, hotels, and sightseeing.',
                'content' => "## At the Airport\nbaggage claim, boarding pass, departure gate, customs, runway, terminal, check-in, carry-on luggage, layover, connecting flight\n\n## Accommodation\ncheck in / check out, room service, receptionist, amenities, complimentary breakfast, suite, hostel, Airbnb, vacancy, reservation\n\n## Sightseeing\nguided tour, landmark, monument, itinerary, souvenir, off the beaten track, tourist trap, local cuisine, day trip, sightseeing\n\n## Useful Phrases\n- I'd like to book a room for two nights.\n- What time is check-out?\n- Is breakfast included?\n- Can you recommend a good restaurant nearby?\n- Where is the nearest metro station?\n\n## Travel Adjectives\nadventurous, breathtaking, exotic, remote, bustling, serene, picturesque, cosmopolitan",
                'vocabulary' => [
                    ['word' => 'itinerary', 'translation' => 'маршрут / план поездки'],
                    ['word' => 'layover', 'translation' => 'пересадка'],
                    ['word' => 'boarding pass', 'translation' => 'посадочный талон'],
                    ['word' => 'landmark', 'translation' => 'достопримечательность'],
                    ['word' => 'souvenir', 'translation' => 'сувенир'],
                    ['word' => 'breathtaking', 'translation' => 'захватывающий дух'],
                    ['word' => 'amenities', 'translation' => 'удобства'],
                    ['word' => 'customs', 'translation' => 'таможня'],
                ],
            ],
            [
                'title' => 'Business English: Meetings & Negotiations',
                'description' => 'Professional vocabulary for the office, presentations, and deals.',
                'content' => "## Meeting Vocabulary\nagenda, minutes, chairperson, stakeholder, action points, AOB (any other business), quorum, adjourn, follow up, conference call\n\n## Negotiation Phrases\n- That's a fair point.\n- I'm afraid that's not acceptable.\n- Could we meet halfway?\n- We'd be willing to consider...\n- Let's come back to that.\n- I think we can work with that.\n- That's a deal.\n\n## Email Phrases\n- I am writing with regard to...\n- Please find attached...\n- I look forward to hearing from you.\n- As per our previous conversation...\n- Could you please clarify...\n- I apologise for the delay.\n\n## Business Idioms\n- Get the ball rolling — start something\n- Touch base — make contact\n- Think outside the box — be creative\n- On the same page — in agreement\n- Ballpark figure — approximate number",
                'vocabulary' => [
                    ['word' => 'agenda', 'translation' => 'повестка дня'],
                    ['word' => 'stakeholder', 'translation' => 'заинтересованная сторона'],
                    ['word' => 'negotiate', 'translation' => 'вести переговоры'],
                    ['word' => 'compromise', 'translation' => 'компромисс'],
                    ['word' => 'adjourn', 'translation' => 'перенести / отложить'],
                    ['word' => 'ballpark figure', 'translation' => 'приблизительная цифра'],
                    ['word' => 'follow up', 'translation' => 'следить / продолжать'],
                ],
            ],
            [
                'title' => 'Technology & Digital World',
                'description' => 'Modern tech vocabulary: AI, social media, cybersecurity, and more.',
                'content' => "## Internet & Social Media\nviral, trending, influencer, algorithm, engagement, hashtag, platform, content creator, stream, upload/download\n\n## Software & Devices\noperating system, update/upgrade, reboot, crash, glitch, bug, software, hardware, app, interface\n\n## AI & Data\nartificial intelligence, machine learning, data privacy, encryption, algorithm, automation, chatbot, neural network, cloud computing, big data\n\n## Cybersecurity\nhacker, phishing, malware, firewall, two-factor authentication, data breach, VPN, password manager, spam, vulnerability\n\n## Useful Verbs\nto scroll, to swipe, to tap, to sync, to backup, to stream, to upload, to download, to encrypt, to debug\n\n## Common Phrases\n- My phone is running low on storage.\n- The app keeps crashing.\n- Did you update your software?\n- I'll send you a link.\n- It went viral overnight.",
                'vocabulary' => [
                    ['word' => 'algorithm', 'translation' => 'алгоритм'],
                    ['word' => 'encryption', 'translation' => 'шифрование'],
                    ['word' => 'phishing', 'translation' => 'фишинг'],
                    ['word' => 'neural network', 'translation' => 'нейронная сеть'],
                    ['word' => 'automation', 'translation' => 'автоматизация'],
                    ['word' => 'vulnerability', 'translation' => 'уязвимость'],
                    ['word' => 'data breach', 'translation' => 'утечка данных'],
                ],
            ],
            [
                'title' => 'Food & Cooking Vocabulary',
                'description' => 'Kitchen verbs, cooking methods, restaurant phrases, and food adjectives.',
                'content' => "## Cooking Methods\nbake, boil, fry, steam, grill, roast, simmer, sauté, poach, marinate\n\n## Taste & Texture\nsweet, sour, salty, bitter, spicy, bland, savoury, creamy, crunchy, tender, juicy, chewy, rich, refreshing\n\n## At a Restaurant\n- Can I see the menu, please?\n- I'd like to order the...\n- What do you recommend?\n- I'm allergic to nuts.\n- Is this dish vegetarian/vegan?\n- Could we have the bill, please?\n- A table for two, please.\n\n## Kitchen Equipment\noven, frying pan, saucepan, chopping board, colander, whisk, spatula, blender, rolling pin, ladle\n\n## Ingredients\nflour, yeast, baking powder, stock, marinade, dressing, seasoning, garnish, zest, herbs",
                'vocabulary' => [
                    ['word' => 'sauté', 'translation' => 'обжаривать на небольшом огне'],
                    ['word' => 'simmer', 'translation' => 'томить / кипятить на медленном огне'],
                    ['word' => 'savoury', 'translation' => 'солёный / несладкий'],
                    ['word' => 'bland', 'translation' => 'пресный / безвкусный'],
                    ['word' => 'garnish', 'translation' => 'украшение блюда'],
                    ['word' => 'marinate', 'translation' => 'мариновать'],
                    ['word' => 'colander', 'translation' => 'дуршлаг'],
                ],
            ],
            [
                'title' => 'Health & Medicine Vocabulary',
                'description' => 'Medical terms, symptoms, and phrases for doctor visits.',
                'content' => "## Common Symptoms\nheadache, sore throat, fever, cough, nausea, dizziness, fatigue, rash, swelling, shortness of breath\n\n## At the Doctor\n- I've been feeling unwell for three days.\n- I have a sharp pain in my chest.\n- I'm allergic to penicillin.\n- Could you prescribe something for the pain?\n- How often should I take this medication?\n\n## Medical Procedures\nblood test, X-ray, MRI scan, vaccination, surgery, physical examination, checkup, prescription, diagnosis, treatment\n\n## Healthy Lifestyle\nbalanced diet, physical activity, mental health, hydration, sleep hygiene, stress management, immune system, metabolism\n\n## Medical Professionals\nGP (General Practitioner), surgeon, specialist, pharmacist, physiotherapist, dentist, psychiatrist, paramedic",
                'vocabulary' => [
                    ['word' => 'symptom', 'translation' => 'симптом'],
                    ['word' => 'diagnosis', 'translation' => 'диагноз'],
                    ['word' => 'prescription', 'translation' => 'рецепт'],
                    ['word' => 'vaccination', 'translation' => 'вакцинация'],
                    ['word' => 'fatigue', 'translation' => 'усталость / истощение'],
                    ['word' => 'metabolism', 'translation' => 'обмен веществ'],
                    ['word' => 'physiotherapist', 'translation' => 'физиотерапевт'],
                ],
            ],

            // ── CONVERSATION ─────────────────────────────────────────────
            [
                'title' => 'Small Talk & Social Phrases',
                'description' => 'How to start conversations, keep them going, and end them politely.',
                'content' => "## Starting a Conversation\n- Lovely weather today, isn't it?\n- Have you been waiting long?\n- Is this your first time here?\n- How do you know [name]?\n- Are you from around here?\n\n## Keeping It Going\n- That's really interesting!\n- Tell me more about that.\n- I know exactly what you mean.\n- Same here! / Me too!\n- Funnily enough, I...\n- What do you think about...?\n\n## Showing Interest\n- Really? / Oh wow! / No way!\n- How did that go?\n- What happened next?\n- That must have been...\n\n## Ending Politely\n- It was lovely talking to you.\n- I should let you go.\n- I don't want to keep you.\n- Let's catch up soon!\n- Take care!\n\n## Useful Fillers\nwell..., you know..., I mean..., actually..., to be honest..., by the way...",
                'vocabulary' => [
                    ['word' => 'small talk', 'translation' => 'светская беседа'],
                    ['word' => 'filler', 'translation' => 'слово-заполнитель'],
                    ['word' => 'to catch up', 'translation' => 'поболтать / узнать новости'],
                    ['word' => 'to keep someone', 'translation' => 'задерживать кого-то'],
                    ['word' => 'to be from around here', 'translation' => 'быть местным'],
                ],
            ],
            [
                'title' => 'Idioms & Phrases: Body Parts',
                'description' => 'English is full of idioms using body parts — learn the most common ones.',
                'content' => "## Head\n- **Use your head** — think logically\n- **Off the top of my head** — from memory, without checking\n- **Keep your head** — stay calm\n- **Head over heels** — completely in love\n\n## Heart\n- **Learn by heart** — memorise\n- **Have a heart of gold** — be very kind\n- **Take something to heart** — be deeply affected\n- **Wear your heart on your sleeve** — show emotions openly\n\n## Hand\n- **Give someone a hand** — help or applaud\n- **Get out of hand** — become uncontrollable\n- **Have your hands full** — be very busy\n- **First-hand** — directly from the source\n\n## Eye\n- **Turn a blind eye** — ignore something on purpose\n- **See eye to eye** — agree\n- **Keep an eye on** — watch carefully\n- **In the blink of an eye** — very quickly\n\n## Foot / Leg\n- **Put your foot in it** — say something awkward\n- **Break a leg!** — good luck!\n- **Get off on the right foot** — start well",
                'vocabulary' => [
                    ['word' => 'idiom', 'translation' => 'идиома'],
                    ['word' => 'head over heels', 'translation' => 'без памяти влюблён'],
                    ['word' => 'turn a blind eye', 'translation' => 'закрывать глаза на что-то'],
                    ['word' => 'break a leg', 'translation' => 'ни пуха ни пера (пожелание удачи)'],
                    ['word' => 'wear your heart on your sleeve', 'translation' => 'не скрывать эмоций'],
                ],
            ],

            // ── BEGINNER ─────────────────────────────────────────────────
            [
                'title' => 'Numbers, Money & Measurements',
                'description' => 'Count, calculate, and talk about prices and sizes in English.',
                'content' => "## Cardinal Numbers\n1–10: one, two, three, four, five, six, seven, eight, nine, ten\n11–20: eleven, twelve, thirteen, fourteen, fifteen, sixteen, seventeen, eighteen, nineteen, twenty\nLarge: hundred, thousand, million, billion\n\n## Ordinal Numbers\nfirst, second, third, fourth, fifth, sixth, tenth, twentieth, hundredth\n\n## Money Phrases\n- How much does it cost? — It costs £12.50\n- That's too expensive. / That's a bargain!\n- Can I pay by card? / Do you accept cash?\n- Could I have a receipt, please?\n- I'd like to exchange some money.\n\n## Measurements\nLength: millimetre, centimetre, metre, kilometre / inch, foot, mile\nWeight: gram, kilogram, pound, ounce\nVolume: millilitre, litre, pint, gallon\nTemperature: degrees Celsius / Fahrenheit\n\n## Useful Expressions\n- It's about 2 metres tall.\n- She weighs roughly 60 kilograms.\n- The temperature is around minus 5.\n- I need half a kilo of flour.",
                'vocabulary' => [
                    ['word' => 'cardinal', 'translation' => 'количественный (о числительном)'],
                    ['word' => 'ordinal', 'translation' => 'порядковый (о числительном)'],
                    ['word' => 'bargain', 'translation' => 'выгодная сделка / дёшево'],
                    ['word' => 'receipt', 'translation' => 'чек'],
                    ['word' => 'exchange', 'translation' => 'обменивать'],
                    ['word' => 'roughly', 'translation' => 'приблизительно'],
                ],
            ],
            [
                'title' => 'Family & Relationships',
                'description' => 'Vocabulary for describing family members, relationships, and life events.',
                'content' => "## Immediate Family\nmother/mum, father/dad, brother, sister, son, daughter, husband, wife, partner\n\n## Extended Family\ngrandmother/grandma, grandfather/grandpa, aunt, uncle, cousin, nephew, niece, in-laws (mother-in-law, father-in-law)\n\n## Relationship Status\nsingle, in a relationship, engaged, married, divorced, widowed, separated\n\n## Life Events\nbe born, grow up, go to school, graduate, get a job, fall in love, get married, have children, retire, pass away\n\n## Describing Relationships\n- They get on really well.\n- We have a lot in common.\n- They've been together for 10 years.\n- She takes after her mother. (resembles)\n- He's the spitting image of his father.\n\n## Family Adjectives\nclose-knit, supportive, dysfunctional, extended, nuclear, blended (step-family)",
                'vocabulary' => [
                    ['word' => 'nephew', 'translation' => 'племянник'],
                    ['word' => 'niece', 'translation' => 'племянница'],
                    ['word' => 'engaged', 'translation' => 'помолвленный'],
                    ['word' => 'widowed', 'translation' => 'вдовый/вдовая'],
                    ['word' => 'close-knit', 'translation' => 'дружная (о семье)'],
                    ['word' => 'take after', 'translation' => 'быть похожим на (родителя)'],
                    ['word' => 'blended family', 'translation' => 'смешанная семья'],
                ],
            ],

            // ── INTERMEDIATE ──────────────────────────────────────────────
            [
                'title' => 'Passive Voice: All Tenses',
                'description' => 'Learn how to form and use the passive voice across different tenses.',
                'content' => "## What is the Passive?\nWe use the passive when the action is more important than who does it.\nActive: Someone built this bridge in 1901.\nPassive: This bridge **was built** in 1901.\n\n## Formation: be + past participle\n| Tense | Active | Passive |\n|---|---|---|\n| Present Simple | they make | it **is made** |\n| Present Continuous | they are making | it **is being made** |\n| Past Simple | they made | it **was made** |\n| Past Continuous | they were making | it **was being made** |\n| Present Perfect | they have made | it **has been made** |\n| Future (will) | they will make | it **will be made** |\n\n## When to Use Passive\n- When the agent is unknown: *My car was stolen.*\n- When the agent is obvious: *The suspect was arrested.*\n- In formal/scientific writing: *The experiment was conducted...*\n- To avoid blame: *Mistakes were made.*\n\n## By + Agent\n- The novel **was written** by Tolstoy.\n- The window **was broken** by the children.",
                'vocabulary' => [
                    ['word' => 'passive voice', 'translation' => 'страдательный залог'],
                    ['word' => 'active voice', 'translation' => 'действительный залог'],
                    ['word' => 'past participle', 'translation' => 'причастие прошедшего времени'],
                    ['word' => 'agent', 'translation' => 'исполнитель действия'],
                    ['word' => 'conduct', 'translation' => 'проводить (эксперимент)'],
                    ['word' => 'suspect', 'translation' => 'подозреваемый'],
                ],
            ],
            [
                'title' => 'Reported Speech',
                'description' => 'How to report what someone said — statements, questions, and commands.',
                'content' => "## Direct vs Reported Speech\nDirect: She said, \"I **am** tired.\"\nReported: She said (that) she **was** tired.\n\n## Tense Backshift\n| Direct | Reported |\n|---|---|\n| am/is/are | was/were |\n| was/were | had been |\n| will | would |\n| can | could |\n| have/has | had |\n| do/does | did |\n\n## Reporting Verbs\nsaid, told, asked, explained, admitted, denied, promised, warned, suggested, claimed\n\n## Reporting Questions\nYes/No questions → if/whether:\n- \"Are you coming?\" → He asked **if** I was coming.\n\nWh-questions → same question word:\n- \"Where do you live?\" → She asked **where** I lived.\n\n## Reporting Commands\nTell/ask + object + to-infinitive:\n- \"Close the door!\" → He told me **to close** the door.\n- \"Please don't shout.\" → She asked me **not to shout**.\n\n## Time/Place Changes\nnow→then, today→that day, here→there, yesterday→the day before, tomorrow→the next day",
                'vocabulary' => [
                    ['word' => 'reported speech', 'translation' => 'косвенная речь'],
                    ['word' => 'backshift', 'translation' => 'сдвиг времён назад'],
                    ['word' => 'admit', 'translation' => 'признавать'],
                    ['word' => 'deny', 'translation' => 'отрицать'],
                    ['word' => 'claim', 'translation' => 'утверждать'],
                    ['word' => 'warn', 'translation' => 'предупреждать'],
                ],
            ],
            [
                'title' => 'Comparatives & Superlatives',
                'description' => 'Compare people, places, and things using the right grammatical forms.',
                'content' => "## Comparative (comparing 2 things)\nShort adj + **-er** + than: tall → **taller than**, fast → **faster than**\n**More** + long adj + than: beautiful → **more beautiful than**\nIrregular: good → **better**, bad → **worse**, far → **further/farther**\n\n## Superlative (comparing 3+ things)\nThe + short adj + **-est**: tall → **the tallest**, fast → **the fastest**\nThe + **most** + long adj: beautiful → **the most beautiful**\nIrregular: good → **the best**, bad → **the worst**, far → **the furthest**\n\n## Spelling Rules\n- One vowel + consonant: big → bigg**er**, bigg**est**\n- Ending in -y: happy → happ**ier**, happ**iest**\n- Ending in -e: nice → nic**er**, nic**est**\n\n## Modifiers\n- **Much/far** bigger (big difference)\n- **A bit/slightly** taller (small difference)\n- **Just as** tall as (equal)\n- **Not as** tall as (less)\n\n## Examples\n- London is **much bigger than** my hometown.\n- This is **the most interesting** book I've ever read.\n- She's **not as experienced as** her colleague.",
                'vocabulary' => [
                    ['word' => 'comparative', 'translation' => 'сравнительная степень'],
                    ['word' => 'superlative', 'translation' => 'превосходная степень'],
                    ['word' => 'irregular', 'translation' => 'неправильный'],
                    ['word' => 'modifier', 'translation' => 'усилитель/смягчитель'],
                    ['word' => 'slightly', 'translation' => 'слегка / немного'],
                ],
            ],
            [
                'title' => 'Collocations: Make vs Do',
                'description' => 'One of the most common mistakes — learn which verbs go with which nouns.',
                'content' => "## MAKE — creating, producing, preparing\n**Make** + : a mistake, a decision, a suggestion, a complaint, a promise, a noise, an effort, progress, a phone call, a difference, friends, money, a speech, plans, an excuse, breakfast/lunch/dinner\n\n## DO — activities, work, tasks\n**Do** + : homework, the washing up, the dishes, exercise, research, business, someone a favour, your best, harm, good, a course, chores, the shopping\n\n## Tricky Ones\n- Make an appointment ✓ (not do)\n- Do an exam ✓ (not make) — *BUT* make a test (create one)\n- Make a mess ✓ (not do)\n- Do the housework ✓ (not make)\n- Make a bed ✓ (not do)\n\n## Example Sentences\n- Could you **do me a favour**?\n- She **made a complaint** to the manager.\n- I need to **do some research** for my essay.\n- He **made an excuse** for being late.\n- Don't forget to **do the washing up**!\n- We **made a decision** to move abroad.",
                'vocabulary' => [
                    ['word' => 'collocation', 'translation' => 'устойчивое словосочетание'],
                    ['word' => 'chores', 'translation' => 'домашние обязанности'],
                    ['word' => 'complaint', 'translation' => 'жалоба'],
                    ['word' => 'favour', 'translation' => 'одолжение'],
                    ['word' => 'appointment', 'translation' => 'встреча / запись (к врачу)'],
                ],
            ],
            [
                'title' => 'Environment & Climate Change',
                'description' => 'Vocabulary and phrases for one of the most important topics of our time.',
                'content' => "## Key Terms\nglobal warming, greenhouse effect, carbon footprint, renewable energy, fossil fuels, deforestation, biodiversity, ecosystem, sustainability, climate change\n\n## Environmental Problems\n- air/water/soil pollution\n- melting ice caps / rising sea levels\n- ozone layer depletion\n- habitat destruction\n- endangered species\n- plastic waste / landfill\n- acid rain\n\n## Solutions\n- solar/wind/hydro power\n- recycling and composting\n- electric vehicles\n- reducing single-use plastics\n- reforestation / tree planting\n- carbon offsetting\n- energy efficiency\n\n## Useful Phrases\n- We need to reduce our carbon footprint.\n- Governments must invest in renewable energy.\n- Climate change is an existential threat.\n- We should switch to sustainable alternatives.\n- Every individual can make a difference.\n\n## Verbs\nto pollute, to recycle, to conserve, to emit, to deplete, to sustain, to threaten, to devastate",
                'vocabulary' => [
                    ['word' => 'greenhouse effect', 'translation' => 'парниковый эффект'],
                    ['word' => 'carbon footprint', 'translation' => 'углеродный след'],
                    ['word' => 'deforestation', 'translation' => 'вырубка лесов'],
                    ['word' => 'biodiversity', 'translation' => 'биоразнообразие'],
                    ['word' => 'renewable energy', 'translation' => 'возобновляемая энергия'],
                    ['word' => 'fossil fuels', 'translation' => 'ископаемое топливо'],
                    ['word' => 'sustainability', 'translation' => 'устойчивость / экологичность'],
                    ['word' => 'carbon offsetting', 'translation' => 'компенсация выбросов углерода'],
                ],
            ],
            [
                'title' => 'Emotions & Feelings Vocabulary',
                'description' => 'Express yourself accurately with a rich vocabulary of emotions.',
                'content' => "## Basic Emotions\nhappy, sad, angry, scared/afraid, surprised, disgusted, confused\n\n## More Precise Words\n| Basic | More precise |\n|---|---|\n| happy | delighted, ecstatic, content, cheerful, elated |\n| sad | miserable, heartbroken, devastated, gloomy, melancholy |\n| angry | furious, irritated, frustrated, annoyed, outraged |\n| scared | terrified, anxious, nervous, apprehensive, uneasy |\n| surprised | astonished, amazed, stunned, shocked, bewildered |\n\n## Emotional Phrases\n- I'm feeling under the weather. (not well)\n- She's on cloud nine. (very happy)\n- He's feeling blue. (sad)\n- I'm at my wit's end. (frustrated, don't know what to do)\n- She's a bundle of nerves. (very anxious)\n\n## Expressing Emotions\n- I was over the moon when I heard the news!\n- It broke my heart to see her cry.\n- I can't help feeling anxious about the exam.\n- He was absolutely livid.\n- I'm a bit down today — not sure why.",
                'vocabulary' => [
                    ['word' => 'ecstatic', 'translation' => 'в восторге / на седьмом небе'],
                    ['word' => 'melancholy', 'translation' => 'меланхолия / грусть'],
                    ['word' => 'furious', 'translation' => 'в ярости'],
                    ['word' => 'apprehensive', 'translation' => 'встревоженный / опасающийся'],
                    ['word' => 'bewildered', 'translation' => 'растерянный / озадаченный'],
                    ['word' => 'livid', 'translation' => 'в бешенстве'],
                    ['word' => 'on cloud nine', 'translation' => 'на седьмом небе от счастья'],
                ],
            ],

            // ── IELTS / ADVANCED ─────────────────────────────────────────
            [
                'title' => 'IELTS Writing: Task 2 Essay Structure',
                'description' => 'How to write a high-scoring IELTS opinion essay.',
                'content' => "## Essay Types\n1. Opinion (Agree/Disagree)\n2. Discussion (Both Views)\n3. Problem/Solution\n4. Advantage/Disadvantage\n5. Two-part Question\n\n## Structure (4 paragraphs, 250+ words)\n**Introduction** (50 words)\n- Paraphrase the question\n- State your position clearly\n\n**Body Paragraph 1** (90 words)\n- Topic sentence\n- Explanation / Reason\n- Example\n\n**Body Paragraph 2** (90 words)\n- Contrasting point or second argument\n- Development\n- Example\n\n**Conclusion** (30 words)\n- Restate opinion\n- No new information!\n\n## Useful Linking Words\nFirstly, Furthermore, However, Nevertheless, In contrast, As a result, Therefore, In conclusion, To sum up, Despite this\n\n## Band 7+ Vocabulary\ncontroversial, inevitable, substantial, detrimental, advocate, perspective, counterargument, empirical, scrutinise, detrimental",
                'vocabulary' => [
                    ['word' => 'paraphrase', 'translation' => 'перефразировать'],
                    ['word' => 'counterargument', 'translation' => 'контраргумент'],
                    ['word' => 'detrimental', 'translation' => 'вредный / пагубный'],
                    ['word' => 'substantial', 'translation' => 'существенный'],
                    ['word' => 'inevitable', 'translation' => 'неизбежный'],
                    ['word' => 'scrutinise', 'translation' => 'тщательно изучать'],
                    ['word' => 'empirical', 'translation' => 'эмпирический'],
                ],
            ],
            [
                'title' => 'Phrasal Verbs: Top 40 Most Common',
                'description' => 'Phrasal verbs are everywhere in spoken English — master the most used ones.',
                'content' => "## Daily Life\n- **wake up** — stop sleeping\n- **get up** — rise from bed\n- **put on** — wear clothing\n- **take off** — remove clothing; (plane) leave the ground\n- **turn on/off** — start/stop a device\n- **run out of** — have no more of something\n- **give up** — stop trying; quit\n- **come up with** — think of an idea\n\n## Relationships\n- **get on with** — have a good relationship\n- **fall out with** — have an argument and stop being friendly\n- **make up** — reconcile after argument\n- **look after** — take care of\n- **bring up** — raise a child; mention a topic\n- **ask out** — invite on a date\n\n## Work & Study\n- **carry out** — do/complete a task\n- **find out** — discover/learn\n- **look up** — search for information\n- **go over** — review\n- **hand in** — submit work\n- **put off** — postpone\n- **set up** — establish/arrange\n- **sort out** — organise/resolve\n\n## Movement\n- **show up** — arrive\n- **pick up** — collect; learn quickly\n- **drop off** — leave someone somewhere\n- **get off** — leave transport\n- **head off** — leave",
                'vocabulary' => [
                    ['word' => 'phrasal verb', 'translation' => 'фразовый глагол'],
                    ['word' => 'come up with', 'translation' => 'придумать'],
                    ['word' => 'put off', 'translation' => 'откладывать'],
                    ['word' => 'carry out', 'translation' => 'выполнять'],
                    ['word' => 'sort out', 'translation' => 'разобраться'],
                    ['word' => 'fall out with', 'translation' => 'поссориться с'],
                    ['word' => 'run out of', 'translation' => 'закончиться (запасы)'],
                ],
            ],
        ];

        $exercisesData = [

            // Present Simple vs Continuous ─────────────────────────────
            [
                'lesson_index' => 0,
                'title' => 'Present Tenses: Choose the Right Form',
                'type' => 'multiple_choice',
                'difficulty' => 'easy',
                'description' => 'Select the correct present tense form.',
                'questions' => [
                    ['question' => 'She ___ (work) at a bank. It\'s her job.', 'options' => ['is working', 'works', 'work', 'has worked'], 'correct_answer' => 'works'],
                    ['question' => 'Quiet! The baby ___ (sleep).', 'options' => ['sleeps', 'is sleeping', 'sleep', 'has slept'], 'correct_answer' => 'is sleeping'],
                    ['question' => 'I usually ___ (have) coffee in the morning.', 'options' => ['am having', 'have', 'has', 'is having'], 'correct_answer' => 'have'],
                    ['question' => 'Look! It ___ (rain) outside.', 'options' => ['rains', 'is raining', 'rained', 'has rained'], 'correct_answer' => 'is raining'],
                    ['question' => 'Water ___ (boil) at 100 degrees Celsius.', 'options' => ['is boiling', 'boils', 'boil', 'boiled'], 'correct_answer' => 'boils'],
                    ['question' => 'They ___ (stay) at a hotel this week.', 'options' => ['stay', 'stays', 'are staying', 'stayed'], 'correct_answer' => 'are staying'],
                    ['question' => 'He ___ (not/like) spicy food.', 'options' => ["doesn't like", "isn't liking", "don't like", "not likes"], 'correct_answer' => "doesn't like"],
                ],
            ],
            [
                'lesson_index' => 0,
                'title' => 'Fill in: Present Simple or Continuous',
                'type' => 'fill_blank',
                'difficulty' => 'medium',
                'description' => 'Write the correct form of the verb in brackets.',
                'questions' => [
                    ['question' => 'I ___ (study) English every day.', 'correct_answer' => 'study'],
                    ['question' => 'She ___ (talk) on the phone right now.', 'correct_answer' => 'is talking'],
                    ['question' => 'We ___ (not/understand) this exercise.', 'correct_answer' => "don't understand"],
                    ['question' => 'The Earth ___ (go) around the Sun.', 'correct_answer' => 'goes'],
                    ['question' => 'I ___ (think) this movie is boring.', 'correct_answer' => 'think'],
                ],
            ],

            // Past Tenses ──────────────────────────────────────────────
            [
                'lesson_index' => 1,
                'title' => 'Past Simple vs Past Continuous Quiz',
                'type' => 'multiple_choice',
                'difficulty' => 'medium',
                'description' => 'Choose the correct past tense.',
                'questions' => [
                    ['question' => 'I ___ TV when she called.', 'options' => ['watched', 'was watching', 'am watching', 'watch'], 'correct_answer' => 'was watching'],
                    ['question' => 'They ___ to London last year.', 'options' => ['were travelling', 'travel', 'travelled', 'have travelled'], 'correct_answer' => 'travelled'],
                    ['question' => 'While I ___ a shower, someone knocked on the door.', 'options' => ['take', 'took', 'was taking', 'taken'], 'correct_answer' => 'was taking'],
                    ['question' => 'She ___ (not/go) to the party last night.', 'options' => ["wasn't going", "didn't go", "don't go", "hadn't gone"], 'correct_answer' => "didn't go"],
                    ['question' => 'He ___ breakfast when the fire alarm went off.', 'options' => ['has had', 'was having', 'had', 'is having'], 'correct_answer' => 'was having'],
                    ['question' => '___ you see the match yesterday?', 'options' => ['Were', 'Have', 'Did', 'Do'], 'correct_answer' => 'Did'],
                ],
            ],

            // Conditionals ────────────────────────────────────────────
            [
                'lesson_index' => 4,
                'title' => 'Conditionals: Match the Halves',
                'type' => 'matching',
                'difficulty' => 'medium',
                'description' => 'Match the if-clause with the correct result clause.',
                'questions' => [
                    ['left' => 'If I were you,', 'right' => 'I would apologise.'],
                    ['left' => 'If it rains tomorrow,', 'right' => 'we will cancel the picnic.'],
                    ['left' => 'If you heat ice,', 'right' => 'it melts.'],
                    ['left' => 'If she had studied harder,', 'right' => 'she would have passed.'],
                    ['left' => 'If I had more time,', 'right' => 'I would learn the guitar.'],
                ],
            ],
            [
                'lesson_index' => 4,
                'title' => 'Fill in the Conditional',
                'type' => 'fill_blank',
                'difficulty' => 'hard',
                'description' => 'Complete using the correct conditional form.',
                'questions' => [
                    ['question' => 'If I ___ (be) taller, I would play basketball professionally.', 'correct_answer' => 'were'],
                    ['question' => 'If she ___ (study), she will pass the exam.', 'correct_answer' => 'studies'],
                    ['question' => 'If they had left earlier, they ___ (not/miss) the train.', 'correct_answer' => "wouldn't have missed"],
                    ['question' => 'Water freezes if the temperature ___ (drop) below 0°C.', 'correct_answer' => 'drops'],
                    ['question' => 'I would call you if I ___ (have) your number.', 'correct_answer' => 'had'],
                ],
            ],

            // Articles ────────────────────────────────────────────────
            [
                'lesson_index' => 3,
                'title' => 'Articles: A, An, The, or Nothing?',
                'type' => 'multiple_choice',
                'difficulty' => 'easy',
                'description' => 'Choose the correct article (or no article).',
                'questions' => [
                    ['question' => 'I saw ___ elephant at the zoo.', 'options' => ['a', 'an', 'the', '—'], 'correct_answer' => 'an'],
                    ['question' => '___ sun rises in the east.', 'options' => ['A', 'An', 'The', '—'], 'correct_answer' => 'The'],
                    ['question' => 'She plays ___ piano beautifully.', 'options' => ['a', 'an', 'the', '—'], 'correct_answer' => 'the'],
                    ['question' => 'I had ___ lunch with my boss.', 'options' => ['a', 'an', 'the', '—'], 'correct_answer' => '—'],
                    ['question' => 'He is ___ honest man.', 'options' => ['a', 'an', 'the', '—'], 'correct_answer' => 'an'],
                    ['question' => 'I speak ___ English and French.', 'options' => ['a', 'an', 'the', '—'], 'correct_answer' => '—'],
                    ['question' => 'I visited ___ USA last summer.', 'options' => ['a', 'an', 'the', '—'], 'correct_answer' => 'the'],
                ],
            ],

            // Modal Verbs ─────────────────────────────────────────────
            [
                'lesson_index' => 5,
                'title' => 'Modal Verbs: Choose the Best Option',
                'type' => 'multiple_choice',
                'difficulty' => 'medium',
                'description' => 'Select the most appropriate modal verb.',
                'questions' => [
                    ['question' => 'You ___ smoke in the hospital. It\'s forbidden.', 'options' => ["mustn't", "don't have to", 'couldn\'t', 'shouldn\'t'], 'correct_answer' => "mustn't"],
                    ['question' => 'You ___ bring a gift. It\'s not necessary.', 'options' => ['must not', "don't have to", 'cannot', 'should not'], 'correct_answer' => "don't have to"],
                    ['question' => 'I\'m not sure where John is. He ___ be at home.', 'options' => ['must', 'will', 'might', 'shall'], 'correct_answer' => 'might'],
                    ['question' => 'She\'s not answering — she ___ be sleeping.', 'options' => ['might', 'must', 'should', 'would'], 'correct_answer' => 'must'],
                    ['question' => '___ you pass me the salt, please?', 'options' => ['Will', 'Shall', 'Could', 'Might'], 'correct_answer' => 'Could'],
                    ['question' => 'Students ___ submit assignments by Friday.', 'options' => ['might', 'could', 'must', 'shall'], 'correct_answer' => 'must'],
                ],
            ],

            // Travel ──────────────────────────────────────────────────
            [
                'lesson_index' => 6,
                'title' => 'Travel Vocabulary Match',
                'type' => 'matching',
                'difficulty' => 'easy',
                'description' => 'Match the travel word to its definition.',
                'questions' => [
                    ['left' => 'boarding pass', 'right' => 'document to get on a plane'],
                    ['left' => 'layover', 'right' => 'stop between two flights'],
                    ['left' => 'itinerary', 'right' => 'plan of a journey'],
                    ['left' => 'customs', 'right' => 'border control for goods'],
                    ['left' => 'souvenir', 'right' => 'item bought to remember a place'],
                    ['left' => 'vacancy', 'right' => 'available room in a hotel'],
                ],
            ],
            [
                'lesson_index' => 6,
                'title' => 'At the Airport: Fill in the Blanks',
                'type' => 'fill_blank',
                'difficulty' => 'easy',
                'description' => 'Complete the airport sentences.',
                'questions' => [
                    ['question' => 'Please proceed to ___ gate B12 for your flight.', 'correct_answer' => 'departure'],
                    ['question' => 'After landing, passengers collect bags at the ___ claim area.', 'correct_answer' => 'baggage'],
                    ['question' => 'You need to ___ in at least 2 hours before your flight.', 'correct_answer' => 'check'],
                    ['question' => 'I have a ___ in Dubai before flying to London.', 'correct_answer' => 'layover'],
                    ['question' => 'Your ___ pass must be shown at the gate.', 'correct_answer' => 'boarding'],
                ],
            ],

            // Business ────────────────────────────────────────────────
            [
                'lesson_index' => 7,
                'title' => 'Business Idioms Quiz',
                'type' => 'multiple_choice',
                'difficulty' => 'hard',
                'description' => 'What does this business idiom mean?',
                'questions' => [
                    ['question' => '"Let\'s get the ball rolling" means:', 'options' => ['stop the project', 'start doing something', 'play a sport', 'be confused'], 'correct_answer' => 'start doing something'],
                    ['question' => '"We\'re on the same page" means:', 'options' => ['reading the same book', 'we agree', 'we are confused', 'we are in the same office'], 'correct_answer' => 'we agree'],
                    ['question' => '"That\'s a ballpark figure" means:', 'options' => ['an exact number', 'a sports score', 'an approximate number', 'a high price'], 'correct_answer' => 'an approximate number'],
                    ['question' => '"Think outside the box" means:', 'options' => ['look for a box', 'be creative', 'follow the rules', 'work alone'], 'correct_answer' => 'be creative'],
                    ['question' => '"Touch base" means:', 'options' => ['touch something', 'make brief contact', 'play baseball', 'argue with someone'], 'correct_answer' => 'make brief contact'],
                ],
            ],
            [
                'lesson_index' => 7,
                'title' => 'Business Email Phrases: Match',
                'type' => 'matching',
                'difficulty' => 'medium',
                'description' => 'Match each phrase to its function.',
                'questions' => [
                    ['left' => 'Please find attached...', 'right' => 'sending a file'],
                    ['left' => 'I look forward to hearing from you.', 'right' => 'closing the email'],
                    ['left' => 'I apologise for the delay.', 'right' => 'expressing regret'],
                    ['left' => 'As per our previous conversation...', 'right' => 'referring to earlier discussion'],
                    ['left' => 'Could you please clarify...', 'right' => 'asking for explanation'],
                ],
            ],

            // Technology ──────────────────────────────────────────────
            [
                'lesson_index' => 8,
                'title' => 'Tech Vocabulary: True or False Definitions',
                'type' => 'multiple_choice',
                'difficulty' => 'medium',
                'description' => 'Choose the correct definition for each tech term.',
                'questions' => [
                    ['question' => 'What is "phishing"?', 'options' => ['a type of fish', 'a cyber attack to steal info', 'a programming language', 'a search engine'], 'correct_answer' => 'a cyber attack to steal info'],
                    ['question' => 'What does "cloud computing" mean?', 'options' => ['computing about weather', 'storing/processing data on remote servers', 'a type of hardware', 'a coding language'], 'correct_answer' => 'storing/processing data on remote servers'],
                    ['question' => 'What is a "glitch"?', 'options' => ['a new feature', 'a type of cable', 'a small technical fault', 'a computer game'], 'correct_answer' => 'a small technical fault'],
                    ['question' => 'What does "encryption" do?', 'options' => ['deletes data', 'converts data into a code to protect it', 'speeds up internet', 'fixes bugs'], 'correct_answer' => 'converts data into a code to protect it'],
                    ['question' => 'What is "machine learning"?', 'options' => ['humans learning to use machines', 'AI that improves from data/experience', 'fixing broken computers', 'learning programming'], 'correct_answer' => 'AI that improves from data/experience'],
                ],
            ],

            // Numbers & Money ─────────────────────────────────────────
            [
                'lesson_index' => 13,
                'title' => 'Numbers & Money Quiz',
                'type' => 'multiple_choice',
                'difficulty' => 'easy',
                'description' => 'Test your knowledge of numbers, prices, and measurements.',
                'questions' => [
                    ['question' => 'How do you say 1,500 in English?', 'options' => ['one thousand and five', 'fifteen hundred', 'one five zero zero', 'one thousand five'], 'correct_answer' => 'fifteen hundred'],
                    ['question' => '"That\'s a bargain!" means:', 'options' => ['it\'s very expensive', 'it\'s a great price', 'it\'s broken', 'it\'s ugly'], 'correct_answer' => 'it\'s a great price'],
                    ['question' => 'Which is correct? "The bag weighs ___."', 'options' => ['2 kilometre', '2 kilograms', '2 kilogramme', '2 kilo'], 'correct_answer' => '2 kilograms'],
                    ['question' => 'What does "roughly" mean in "It costs roughly £50"?', 'options' => ['exactly', 'approximately', 'at least', 'less than'], 'correct_answer' => 'approximately'],
                    ['question' => 'The ordinal form of 3 is:', 'options' => ['three', 'threeth', 'third', 'thrice'], 'correct_answer' => 'third'],
                ],
            ],
            [
                'lesson_index' => 13,
                'title' => 'Money & Shopping Phrases',
                'type' => 'matching',
                'difficulty' => 'easy',
                'description' => 'Match each phrase to the correct situation.',
                'questions' => [
                    ['left' => 'Can I pay by card?', 'right' => 'asking about payment method'],
                    ['left' => 'Could I have a receipt?', 'right' => 'asking for proof of purchase'],
                    ['left' => 'How much does it cost?', 'right' => 'asking for the price'],
                    ['left' => 'That\'s too expensive.', 'right' => 'saying the price is too high'],
                    ['left' => 'I\'d like to exchange money.', 'right' => 'converting currency'],
                ],
            ],

            // Family ──────────────────────────────────────────────────
            [
                'lesson_index' => 14,
                'title' => 'Family Vocabulary Quiz',
                'type' => 'multiple_choice',
                'difficulty' => 'easy',
                'description' => 'Choose the correct family term.',
                'questions' => [
                    ['question' => 'Your mother\'s sister is your:', 'options' => ['niece', 'cousin', 'aunt', 'sister-in-law'], 'correct_answer' => 'aunt'],
                    ['question' => 'Your brother\'s son is your:', 'options' => ['cousin', 'nephew', 'uncle', 'grandson'], 'correct_answer' => 'nephew'],
                    ['question' => '"She takes after her mother" means:', 'options' => ['she follows her mother', 'she looks/acts like her mother', 'she takes care of her', 'she argues with her'], 'correct_answer' => 'she looks/acts like her mother'],
                    ['question' => 'A "close-knit family" is:', 'options' => ['a very small family', 'a family that knits together', 'a family with strong bonds', 'a formal family'], 'correct_answer' => 'a family with strong bonds'],
                    ['question' => 'A "blended family" includes:', 'options' => ['only biological children', 'step-parents and step-children', 'grandparents living together', 'single parent families'], 'correct_answer' => 'step-parents and step-children'],
                ],
            ],

            // Passive Voice ───────────────────────────────────────────
            [
                'lesson_index' => 15,
                'title' => 'Passive Voice: Choose the Correct Form',
                'type' => 'multiple_choice',
                'difficulty' => 'medium',
                'description' => 'Select the correct passive construction.',
                'questions' => [
                    ['question' => 'The Eiffel Tower ___ in 1889.', 'options' => ['built', 'was built', 'is built', 'has built'], 'correct_answer' => 'was built'],
                    ['question' => 'English ___ all over the world.', 'options' => ['speaks', 'is speaking', 'is spoken', 'spoken'], 'correct_answer' => 'is spoken'],
                    ['question' => 'The report ___ by the manager right now.', 'options' => ['is written', 'is being written', 'was written', 'has written'], 'correct_answer' => 'is being written'],
                    ['question' => 'My wallet ___! I can\'t find it anywhere.', 'options' => ['stole', 'has been stolen', 'was stealing', 'is stealing'], 'correct_answer' => 'has been stolen'],
                    ['question' => 'The new bridge ___ next year.', 'options' => ['will build', 'is built', 'will be built', 'was built'], 'correct_answer' => 'will be built'],
                    ['question' => 'The letter ___ by Shakespeare himself.', 'options' => ['wrote', 'was written', 'is written', 'has wrote'], 'correct_answer' => 'was written'],
                ],
            ],
            [
                'lesson_index' => 15,
                'title' => 'Active to Passive: Fill in the Blank',
                'type' => 'fill_blank',
                'difficulty' => 'hard',
                'description' => 'Rewrite using the passive voice.',
                'questions' => [
                    ['question' => 'Someone broke the window. → The window ___ (break).', 'correct_answer' => 'was broken'],
                    ['question' => 'They are building a new road. → A new road ___ (build).', 'correct_answer' => 'is being built'],
                    ['question' => 'Shakespeare wrote Hamlet. → Hamlet ___ by Shakespeare. (write)', 'correct_answer' => 'was written'],
                    ['question' => 'They will deliver the package tomorrow. → The package ___ tomorrow. (deliver)', 'correct_answer' => 'will be delivered'],
                    ['question' => 'People speak French in Canada. → French ___ in Canada. (speak)', 'correct_answer' => 'is spoken'],
                ],
            ],

            // Reported Speech ─────────────────────────────────────────
            [
                'lesson_index' => 16,
                'title' => 'Reported Speech: Tense Backshift',
                'type' => 'multiple_choice',
                'difficulty' => 'medium',
                'description' => 'Choose the correct reported speech form.',
                'questions' => [
                    ['question' => '"I am hungry." → She said she ___ hungry.', 'options' => ['is', 'was', 'will be', 'has been'], 'correct_answer' => 'was'],
                    ['question' => '"I will call you." → He promised he ___ me.', 'options' => ['will call', 'would call', 'called', 'has called'], 'correct_answer' => 'would call'],
                    ['question' => '"Are you coming?" → She asked if I ___ coming.', 'options' => ['am', 'was', 'were', 'would be'], 'correct_answer' => 'was'],
                    ['question' => '"Don\'t touch that!" → He told me ___ touch it.', 'options' => ['don\'t', 'not to', 'to not', 'didn\'t'], 'correct_answer' => 'not to'],
                    ['question' => '"I have finished." → She said she ___ finished.', 'options' => ['has', 'had', 'have', 'was'], 'correct_answer' => 'had'],
                ],
            ],
            [
                'lesson_index' => 16,
                'title' => 'Reporting Verbs: Match',
                'type' => 'matching',
                'difficulty' => 'hard',
                'description' => 'Match each direct speech to the correct reporting verb.',
                'questions' => [
                    ['left' => '"I didn\'t steal it!" said John.', 'right' => 'denied'],
                    ['left' => '"I\'ll be there at 8." said Maria.', 'right' => 'promised'],
                    ['left' => '"Watch out for the dog!" he said.', 'right' => 'warned'],
                    ['left' => '"Why don\'t we go to the cinema?" she said.', 'right' => 'suggested'],
                    ['left' => '"Yes, I broke the window." he said.', 'right' => 'admitted'],
                ],
            ],

            // Comparatives ────────────────────────────────────────────
            [
                'lesson_index' => 17,
                'title' => 'Comparatives & Superlatives Quiz',
                'type' => 'multiple_choice',
                'difficulty' => 'easy',
                'description' => 'Choose the correct comparative or superlative form.',
                'questions' => [
                    ['question' => 'Russia is ___ country in the world. (large)', 'options' => ['larger', 'the most large', 'the largest', 'more large'], 'correct_answer' => 'the largest'],
                    ['question' => 'This test is ___ than the last one. (difficult)', 'options' => ['difficulter', 'more difficult', 'most difficult', 'the most difficult'], 'correct_answer' => 'more difficult'],
                    ['question' => 'She runs ___ than her brother. (fast)', 'options' => ['more fast', 'fastest', 'faster', 'the fastest'], 'correct_answer' => 'faster'],
                    ['question' => 'He is ___ student in the class. (good)', 'options' => ['the goodest', 'the most good', 'the best', 'better'], 'correct_answer' => 'the best'],
                    ['question' => 'Today is ___ than yesterday. (cold)', 'options' => ['more cold', 'coldest', 'the coldest', 'colder'], 'correct_answer' => 'colder'],
                    ['question' => 'This is ___ film I have ever seen. (bad)', 'options' => ['the most bad', 'the worst', 'the baddest', 'worse'], 'correct_answer' => 'the worst'],
                ],
            ],
            [
                'lesson_index' => 17,
                'title' => 'Fill in: Comparative or Superlative',
                'type' => 'fill_blank',
                'difficulty' => 'medium',
                'description' => 'Write the correct form of the adjective.',
                'questions' => [
                    ['question' => 'My bag is ___ (heavy) than yours.', 'correct_answer' => 'heavier'],
                    ['question' => 'This is ___ (interesting) book I\'ve ever read.', 'correct_answer' => 'the most interesting'],
                    ['question' => 'She\'s ___ (happy) now than she was last year.', 'correct_answer' => 'happier'],
                    ['question' => 'He drives ___ (carefully) than his wife.', 'correct_answer' => 'more carefully'],
                    ['question' => 'That\'s ___ (bad) excuse I\'ve ever heard!', 'correct_answer' => 'the worst'],
                ],
            ],

            // Make vs Do ──────────────────────────────────────────────
            [
                'lesson_index' => 18,
                'title' => 'Make or Do?',
                'type' => 'multiple_choice',
                'difficulty' => 'medium',
                'description' => 'Choose the correct verb: make or do.',
                'questions' => [
                    ['question' => 'I need to ___ my homework before dinner.', 'options' => ['make', 'do', 'have', 'take'], 'correct_answer' => 'do'],
                    ['question' => 'She ___ a mistake in the calculation.', 'options' => ['did', 'made', 'had', 'took'], 'correct_answer' => 'made'],
                    ['question' => 'Can you ___ me a favour?', 'options' => ['make', 'do', 'give', 'have'], 'correct_answer' => 'do'],
                    ['question' => 'He ___ a promise to be on time.', 'options' => ['did', 'made', 'took', 'had'], 'correct_answer' => 'made'],
                    ['question' => 'I always ___ the dishes after dinner.', 'options' => ['make', 'do', 'wash', 'clean'], 'correct_answer' => 'do'],
                    ['question' => 'They ___ a decision to move to Canada.', 'options' => ['did', 'made', 'took', 'had'], 'correct_answer' => 'made'],
                    ['question' => 'She\'s ___ a course in graphic design.', 'options' => ['making', 'doing', 'having', 'taking'], 'correct_answer' => 'doing'],
                ],
            ],

            // Environment ─────────────────────────────────────────────
            [
                'lesson_index' => 19,
                'title' => 'Environment Vocabulary Match',
                'type' => 'matching',
                'difficulty' => 'medium',
                'description' => 'Match each environmental term to its definition.',
                'questions' => [
                    ['left' => 'carbon footprint', 'right' => 'total CO2 emissions of a person/organisation'],
                    ['left' => 'deforestation', 'right' => 'large-scale cutting down of forests'],
                    ['left' => 'renewable energy', 'right' => 'power from natural, replenishable sources'],
                    ['left' => 'biodiversity', 'right' => 'variety of life in an ecosystem'],
                    ['left' => 'greenhouse effect', 'right' => 'warming caused by trapped gases'],
                    ['left' => 'fossil fuels', 'right' => 'coal, oil, gas formed from ancient organisms'],
                ],
            ],
            [
                'lesson_index' => 19,
                'title' => 'Climate Change: Fill in the Blanks',
                'type' => 'fill_blank',
                'difficulty' => 'hard',
                'description' => 'Complete each sentence with the correct term.',
                'questions' => [
                    ['question' => 'We need to reduce our carbon ___ to fight climate change.', 'correct_answer' => 'footprint'],
                    ['question' => 'Solar and wind power are examples of ___ energy.', 'correct_answer' => 'renewable'],
                    ['question' => 'The melting of ice caps causes ___ sea levels.', 'correct_answer' => 'rising'],
                    ['question' => 'Cutting down forests is called ___.', 'correct_answer' => 'deforestation'],
                    ['question' => 'Burning ___ fuels like oil and coal releases CO2.', 'correct_answer' => 'fossil'],
                ],
            ],

            // Emotions ────────────────────────────────────────────────
            [
                'lesson_index' => 20,
                'title' => 'Emotions: Precise Vocabulary',
                'type' => 'multiple_choice',
                'difficulty' => 'medium',
                'description' => 'Choose the most precise word to describe each emotion.',
                'questions' => [
                    ['question' => 'She was so happy she cried — she was ___.', 'options' => ['content', 'ecstatic', 'cheerful', 'pleased'], 'correct_answer' => 'ecstatic'],
                    ['question' => 'He was shaking with fear — he was ___.', 'options' => ['nervous', 'uneasy', 'terrified', 'apprehensive'], 'correct_answer' => 'terrified'],
                    ['question' => 'She was so angry she couldn\'t speak — she was ___.', 'options' => ['annoyed', 'irritated', 'furious', 'frustrated'], 'correct_answer' => 'furious'],
                    ['question' => '"Feeling blue" means:', 'options' => ['feeling cold', 'feeling sad', 'feeling confused', 'feeling jealous'], 'correct_answer' => 'feeling sad'],
                    ['question' => '"On cloud nine" means:', 'options' => ['very high up', 'extremely happy', 'lost and confused', 'in danger'], 'correct_answer' => 'extremely happy'],
                ],
            ],
            [
                'lesson_index' => 20,
                'title' => 'Emotions: Match the Expression',
                'type' => 'matching',
                'difficulty' => 'medium',
                'description' => 'Match the emotional expression to its meaning.',
                'questions' => [
                    ['left' => 'at my wit\'s end', 'right' => 'completely frustrated, don\'t know what to do'],
                    ['left' => 'a bundle of nerves', 'right' => 'extremely anxious'],
                    ['left' => 'over the moon', 'right' => 'delighted, very happy'],
                    ['left' => 'feeling blue', 'right' => 'feeling sad'],
                    ['left' => 'under the weather', 'right' => 'not feeling well'],
                ],
            ],

            // Phrasal Verbs ───────────────────────────────────────────
            [
                'lesson_index' => 22,
                'title' => 'Phrasal Verbs: What Does It Mean?',
                'type' => 'multiple_choice',
                'difficulty' => 'hard',
                'description' => 'Choose the correct meaning of the phrasal verb.',
                'questions' => [
                    ['question' => '"I\'ll look into the problem tomorrow."', 'options' => ['look at', 'investigate', 'ignore', 'solve immediately'], 'correct_answer' => 'investigate'],
                    ['question' => '"She came up with a brilliant idea."', 'options' => ['discovered by accident', 'thought of', 'copied from someone', 'rejected'], 'correct_answer' => 'thought of'],
                    ['question' => '"He put off the meeting until next week."', 'options' => ['cancelled', 'moved to a later time', 'moved to earlier', 'started'], 'correct_answer' => 'moved to a later time'],
                    ['question' => '"They fell out over money."', 'options' => ['fell physically', 'agreed about', 'had an argument', 'became friends'], 'correct_answer' => 'had an argument'],
                    ['question' => '"I need to sort out my finances."', 'options' => ['earn more money', 'organise/resolve', 'spend money', 'borrow money'], 'correct_answer' => 'organise/resolve'],
                    ['question' => '"Don\'t give up — keep trying!"', 'options' => ['rest', 'stop trying', 'give something away', 'start again'], 'correct_answer' => 'stop trying'],
                ],
            ],
            [
                'lesson_index' => 22,
                'title' => 'Phrasal Verbs: Fill the Gap',
                'type' => 'fill_blank',
                'difficulty' => 'hard',
                'description' => 'Complete the sentence with the correct phrasal verb.',
                'questions' => [
                    ['question' => 'I need to ___ ___ some information before the presentation. (find)', 'correct_answer' => 'find out'],
                    ['question' => 'Can you ___ ___ my cat while I\'m on holiday? (look)', 'correct_answer' => 'look after'],
                    ['question' => 'I\'m going to ___ ___ my notes before the exam. (go)', 'correct_answer' => 'go over'],
                    ['question' => 'She ___ ___ a great plan for the project. (come)', 'correct_answer' => 'came up with'],
                    ['question' => 'Please ___ ___ your homework by Friday. (hand)', 'correct_answer' => 'hand in'],
                ],
            ],

            // Idioms (Body Parts) — index stays 12 ────────────────────
            [
                'lesson_index' => 12,
                'title' => 'Body Idioms: Match the Meaning',
                'type' => 'matching',
                'difficulty' => 'medium',
                'description' => 'Match the idiom to its correct meaning.',
                'questions' => [
                    ['left' => 'learn by heart', 'right' => 'memorise completely'],
                    ['left' => 'turn a blind eye', 'right' => 'ignore something deliberately'],
                    ['left' => 'get out of hand', 'right' => 'become uncontrollable'],
                    ['left' => 'break a leg', 'right' => 'good luck'],
                    ['left' => 'see eye to eye', 'right' => 'agree with someone'],
                    ['left' => 'have your hands full', 'right' => 'be very busy'],
                ],
            ],

            // IELTS ───────────────────────────────────────────────────
            [
                'lesson_index' => 21,
                'title' => 'IELTS Vocabulary: Advanced Words',
                'type' => 'matching',
                'difficulty' => 'hard',
                'description' => 'Match the advanced IELTS word to its meaning.',
                'questions' => [
                    ['left' => 'detrimental', 'right' => 'causing harm or damage'],
                    ['left' => 'inevitable', 'right' => 'impossible to avoid'],
                    ['left' => 'scrutinise', 'right' => 'examine very carefully'],
                    ['left' => 'substantial', 'right' => 'large in size or importance'],
                    ['left' => 'counterargument', 'right' => 'a reason against an opinion'],
                    ['left' => 'empirical', 'right' => 'based on observation or experiment'],
                ],
            ],
            [
                'lesson_index' => 13,
                'title' => 'IELTS Linking Words Quiz',
                'type' => 'multiple_choice',
                'difficulty' => 'hard',
                'description' => 'Choose the best linking word to complete the sentence.',
                'questions' => [
                    ['question' => '___, there are advantages to working from home, such as saving time.', 'options' => ['However', 'Firstly', 'In contrast', 'Despite'], 'correct_answer' => 'Firstly'],
                    ['question' => 'The cost is high. ___, many people still buy it.', 'options' => ['Therefore', 'Furthermore', 'Nevertheless', 'Firstly'], 'correct_answer' => 'Nevertheless'],
                    ['question' => 'She studied hard. ___, she passed the exam.', 'options' => ['However', 'As a result', 'Despite', 'In contrast'], 'correct_answer' => 'As a result'],
                    ['question' => '___ being expensive, the phone sold millions.', 'options' => ['Although', 'Despite', 'However', 'Furthermore'], 'correct_answer' => 'Despite'],
                    ['question' => 'Public transport is cheap. ___, it can be unreliable.', 'options' => ['Therefore', 'As a result', 'However', 'Furthermore'], 'correct_answer' => 'However'],
                ],
            ],

            // Health ──────────────────────────────────────────────────
            [
                'lesson_index' => 10,
                'title' => 'Medical Vocabulary Match',
                'type' => 'matching',
                'difficulty' => 'easy',
                'description' => 'Match the medical term to its meaning.',
                'questions' => [
                    ['left' => 'diagnosis', 'right' => 'identifying a disease or condition'],
                    ['left' => 'prescription', 'right' => 'written order for medicine'],
                    ['left' => 'symptom', 'right' => 'sign of illness'],
                    ['left' => 'vaccination', 'right' => 'injection to prevent disease'],
                    ['left' => 'fatigue', 'right' => 'extreme tiredness'],
                ],
            ],
            [
                'lesson_index' => 10,
                'title' => 'At the Doctor: Fill in the Blanks',
                'type' => 'fill_blank',
                'difficulty' => 'medium',
                'description' => 'Complete the doctor-patient conversation.',
                'questions' => [
                    ['question' => 'I\'ve been feeling ___ for three days — tired and weak.', 'correct_answer' => 'unwell'],
                    ['question' => 'I have a ___ throat and a high temperature.', 'correct_answer' => 'sore'],
                    ['question' => 'The doctor will write a ___ for your medication.', 'correct_answer' => 'prescription'],
                    ['question' => 'Could you ___ me something for the pain?', 'correct_answer' => 'prescribe'],
                    ['question' => 'I\'m ___ to penicillin, so please avoid it.', 'correct_answer' => 'allergic'],
                ],
            ],

            // Food ────────────────────────────────────────────────────
            [
                'lesson_index' => 9,
                'title' => 'Cooking Methods & Taste Vocabulary',
                'type' => 'multiple_choice',
                'difficulty' => 'easy',
                'description' => 'Choose the best word to complete each sentence.',
                'questions' => [
                    ['question' => 'The steak was ___ — so tender and full of flavour!', 'options' => ['bland', 'juicy', 'bitter', 'crunchy'], 'correct_answer' => 'juicy'],
                    ['question' => 'To ___ vegetables means to cook them gently in a little oil.', 'options' => ['boil', 'bake', 'sauté', 'roast'], 'correct_answer' => 'sauté'],
                    ['question' => 'The soup was too ___; she hadn\'t added any salt.', 'options' => ['spicy', 'sour', 'bland', 'sweet'], 'correct_answer' => 'bland'],
                    ['question' => 'Can you ___ the vegetables before adding them to the soup?', 'options' => ['bake', 'chop', 'roast', 'grill'], 'correct_answer' => 'chop'],
                    ['question' => 'The bread had a perfectly ___ crust.', 'options' => ['creamy', 'chewy', 'crunchy', 'bitter'], 'correct_answer' => 'crunchy'],
                ],
            ],

            // Small Talk ──────────────────────────────────────────────
            [
                'lesson_index' => 11,
                'title' => 'Small Talk: What Would You Say?',
                'type' => 'multiple_choice',
                'difficulty' => 'easy',
                'description' => 'Choose the most natural response in each situation.',
                'questions' => [
                    ['question' => 'Someone tells you good news. You respond:', 'options' => ['I don\'t care.', 'That\'s terrible!', 'That\'s amazing! Tell me more.', 'Whatever.'], 'correct_answer' => 'That\'s amazing! Tell me more.'],
                    ['question' => 'You want to politely end a conversation:', 'options' => ['Go away!', 'It was lovely talking to you.', 'I don\'t like you.', 'Goodbye forever.'], 'correct_answer' => 'It was lovely talking to you.'],
                    ['question' => 'You want to ask someone how they know the host at a party:', 'options' => ['Why are you here?', 'Who invited you?', 'How do you know [name]?', 'Are you lost?'], 'correct_answer' => 'How do you know [name]?'],
                    ['question' => 'You didn\'t understand what someone said:', 'options' => ['Repeat!', 'What?!', 'Could you say that again, please?', 'Speak louder.'], 'correct_answer' => 'Could you say that again, please?'],
                    ['question' => 'Someone asks how you are. A natural response is:', 'options' => ['I am fine thank you and you?', 'Not bad, thanks! How about yourself?', 'I exist.', 'Who cares?'], 'correct_answer' => 'Not bad, thanks! How about yourself?'],
                ],
            ],
        ];

        // Create lessons
        $createdLessons = [];
        foreach ($lessons as $lessonData) {
            $lesson = Lesson::create([
                'title'       => $lessonData['title'],
                'description' => $lessonData['description'],
                'content'     => $lessonData['content'],
                'vocabulary'  => $lessonData['vocabulary'],
                'teacher_id'  => $teacher->id,
            ]);
            $createdLessons[] = $lesson;
        }

        // Create exercises
        foreach ($exercisesData as $exData) {
            $lesson = $createdLessons[$exData['lesson_index']] ?? null;
            Exercise::create([
                'title'       => $exData['title'],
                'description' => $exData['description'],
                'type'        => $exData['type'],
                'difficulty'  => $exData['difficulty'],
                'questions'   => $exData['questions'],
                'teacher_id'  => $teacher->id,
                'lesson_id'   => $lesson?->id,
            ]);
        }

        $this->command->info('ContentSeeder: ' . count($createdLessons) . ' lessons and ' . count($exercisesData) . ' exercises created.');
    }
}
