// quote.js
export function getQuote() {
  const quotes = [
    "The best time to plant a tree was 20 years ago. The second best time is now.",
    "Success is not final, failure is not fatal: It is the courage to continue that counts.",
    "Do not wait to strike till the iron is hot, but make it hot by striking.",
    "Success usually comes to those who are too busy to be looking for it.",
    "The harder you work for something, the greater you'll feel when you achieve it.",
    "Don't watch the clock; do what it does. Keep going.",
    "The only place where success comes before work is in the dictionary.",
    "Opportunities don't happen. You create them.",
    "Dream big and dare to fail.",
    "It does not matter how slowly you go as long as you do not stop.",
    "Your time is limited, so don’t waste it living someone else’s life.",
    "I am not a product of my circumstances. I am a product of my decisions.",
    "Hardships often prepare ordinary people for an extraordinary destiny.",
    "Believe you can and you're halfway there.",
    "Act as if what you do makes a difference. It does.",
    "Everything you’ve ever wanted is on the other side of fear.",
    "Start where you are. Use what you have. Do what you can.",
    "Success is walking from failure to failure with no loss of enthusiasm.",
    "Go the extra mile. It’s never crowded there.",
    "Push yourself, because no one else is going to do it for you.",
    "Sometimes later becomes never. Do it now.",
    "Great things never come from comfort zones.",
    "Dream it. Wish it. Do it.",
    "Success doesn’t just find you. You have to go out and get it.",
    "The key to success is to focus on goals, not obstacles.",
    "Don’t stop when you’re tired. Stop when you’re done.",
    "Wake up with determination. Go to bed with satisfaction.",
    "Little things make big days.",
    "It always seems impossible until it’s done.",
    "Stay positive. Work hard. Make it happen.",
    "Don’t limit your challenges. Challenge your limits.",
    "If you’re going through hell, keep going.",
    "Doubt kills more dreams than failure ever will.",
    "Work hard in silence, let your success be your noise.",
    "Discipline is the bridge between goals and accomplishment.",
    "Do something today that your future self will thank you for.",
    "The future depends on what you do today.",
    "Don’t be afraid to give up the good to go for the great.",
    "Action is the foundational key to all success.",
    "Don’t count the days, make the days count.",
    "You don’t have to be great to start, but you have to start to be great.",
    "If you can dream it, you can do it.",
    "Success is the sum of small efforts, repeated day in and day out.",
    "A river cuts through rock not because of its power, but because of its persistence.",
    "If not now, when?",
    "The way to get started is to quit talking and begin doing.",
    "Set your goals high, and don’t stop till you get there.",
    "Don’t wait for opportunity. Create it.",
    "Your limitation—it’s only your imagination.",
    "Sometimes we’re tested not to show our weaknesses, but to discover our strengths.",
    "Great minds discuss ideas; average minds discuss events; small minds discuss people.",
    "If you want to achieve greatness stop asking for permission.",
    "Success is what comes after you stop making excuses.",
    "The best revenge is massive success.",
    "The secret of getting ahead is getting started.",
    "I find that the harder I work, the more luck I seem to have.",
    "You miss 100% of the shots you don’t take.",
    "Success isn’t overnight. It’s when every day you get a little better than the day before.",
    "Strive not to be a success, but rather to be of value.",
    "Do not be embarrassed by your failures, learn from them and start again.",
    "What you get by achieving your goals is not as important as what you become by achieving your goals.",
  ];

  const today = new Date().toISOString().split("T")[0]; // Format: YYYY-MM-DD
  const storedData = JSON.parse(localStorage.getItem("dailyQuote"));

  if (storedData && storedData.date === today) {
    return storedData.quote;
  }

  const randomIndex = Math.floor(Math.random() * quotes.length);
  const newQuote = quotes[randomIndex];

  localStorage.setItem(
    "dailyQuote",
    JSON.stringify({ date: today, quote: newQuote }),
  );

  return newQuote;
}
