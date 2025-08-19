# Feature Implementation: Healthy Food Modal

## 🎯 **Feature Overview**

Implementasi modal khusus untuk card "Makan Sehat" yang muncul saat card diklik, menanyakan "Apa menu makan sehat mu hari ini?" dengan sistem multiple input seperti exercise modal.

## ✨ **New Feature Details**

### **User Interaction Flow:**
1. **Card Click**: User klik pada card "Makan Sehat"
2. **Modal Popup**: Modal muncul dengan pertanyaan "Apa menu makan sehat mu hari ini?"
3. **Multiple Input**: User bisa input beberapa jenis makanan sehat
4. **List Display**: Makanan sehat ditampilkan sebagai tag list dengan warna hijau
5. **Completion**: Card ter-mark completed dengan list makanan sehat

### **UI/UX Features:**
- 🥗 **Healthy Theme**: Green gradient dengan salad/food icon
- 🍎 **Multiple Food Input**: Bisa menambah beberapa menu makan sehat
- 📝 **Real-time List**: Food langsung muncul di list setelah input
- ❌ **Remove Function**: Bisa hapus makanan dari list
- 💾 **Persistent Data**: List tersimpan di card sebagai green tags

## 🔧 **Technical Implementation**

### **1. Modal State Management**
```javascript
// New Alpine.js state variables for healthy food
showHealthyFoodModal: false,    // Modal visibility
healthyFoodInput: '',          // Current input value
healthyFoodList: [],          // Array of healthy foods
```

### **2. Healthy Food Management Functions**
```javascript
// Add healthy food to list
addHealthyFood() {
  if (this.healthyFoodInput.trim()) {
    this.healthyFoodList.push(this.healthyFoodInput.trim());
    this.healthyFoodInput = '';
  }
}

// Remove healthy food from list
removeHealthyFood(index) {
  this.healthyFoodList.splice(index, 1);
}

// Complete healthy eating and save data
handleHealthyFoodComplete() {
  // Save to form.habits[id].notes as JSON
  const healthyFoodData = {
    foods: this.healthyFoodList,
    total: this.healthyFoodList.length
  };
  this.form.habits[habitId].notes = JSON.stringify(healthyFoodData);
}
```

### **3. Data Storage Structure**
```json
{
  "foods": ["Salad", "Buah-buahan", "Sayur rebus", "Smoothie"],
  "total": 4
}
```

### **4. Card Detection Logic**
```php
// Detect healthy food card by name
<?php if ($h['name'] === 'Makan Sehat' || strpos(strtolower($h['name']), 'makan') !== false): ?>
```

## 📱 **Modal Design Features**

### **Visual Elements:**
- **🥗 Salad Icon**: Green gradient circle dengan food/star icon
- **Title**: "Makan Sehat! 🥗"
- **Question**: "Apa menu makan sehat mu hari ini?"
- **Input Field**: Large input dengan placeholder examples
- **Green Theme**: Consistent green color scheme

### **Interactive Elements:**
- **Food Input**: Text input dengan placeholder "Contoh: Salad, Buah-buahan, Sayur rebus..."
- **Enter Support**: Tekan Enter untuk quick add
- **Add Button**: Plus button dengan green gradient
- **Remove Buttons**: X button untuk setiap food item
- **Complete Button**: "Selesai Makan Sehat" dengan green gradient

### **Food List Display:**
- **Green Tags**: Styled tags dengan green gradient background
- **Scrollable Area**: Max height dengan scroll untuk banyak items
- **Visual Feedback**: Hover effects dan smooth transitions
- **Individual Remove**: Each food item dapat dihapus individual

## 🎨 **Design System**

### **Color Palette:**
- **Primary**: Green 500 → Emerald 600 gradient
- **Background**: Green 50 → Emerald 50 gradient
- **Border**: Green 200 → Green 400 focus states
- **Text**: Green 700 untuk primary text
- **Tags**: Green 100 background dengan Green 700 text

### **Card Display Changes:**
- ✅ **Clickable**: Entire card menjadi clickable
- ✅ **Visual Hint**: "Klik card untuk input menu sehat" message
- ✅ **Green Tags**: Food list ditampilkan sebagai green tags
- ✅ **Empty State**: Placeholder text ketika belum ada input
- ✅ **Read-only Toggle**: Switch menjadi status indicator

## 🔄 **Behavior Integration**

### **Modal Triggers Updated:**
- ✅ **Bangun Pagi**: Click card → Wake up time modal
- ✅ **Berolahraga**: Click card → Exercise input modal  
- ✅ **Makan Sehat**: Click card → Healthy food input modal
- ✅ **Other Habits**: Normal toggle behavior

### **Toggle Switch States:**
```php
// Enhanced condition for modal habits
<?php if ($h['code'] === 'bangun_pagi' || $h['code'] === 'berolahraga' || $h['name'] === 'Makan Sehat' || strpos(strtolower($h['name']), 'makan') !== false): ?>
<!-- Read-only toggle switch -->
<?php else: ?>
<!-- Interactive toggle switch -->
<?php endif; ?>
```

### **Card Click Handlers:**
```html
<!-- Multiple click handlers for different modals -->
<?= $h['code'] === 'bangun_pagi' ? '@click="showWakeUpModal = true"' : '' ?>
<?= $h['code'] === 'berolahraga' ? '@click="showExerciseModal = true"' : '' ?>
<?= ($h['name'] === 'Makan Sehat' || strpos(strtolower($h['name']), 'makan') !== false) ? '@click="showHealthyFoodModal = true"' : '' ?>
```

## 💾 **Data Management**

### **Initialization System:**
```javascript
// Initialize healthy food list from existing data
initializeExerciseList() {
  // Exercise initialization...
  
  // Healthy food initialization
  const healthyFoodHabitId = [food_habit_id];
  const existingFoods = this.getHealthyFoodList(healthyFoodHabitId);
  if (existingFoods.length > 0) {
    this.healthyFoodList = [...existingFoods];
  }
}
```

### **Data Retrieval:**
```javascript
// Get healthy food list from stored data
getHealthyFoodList(habitId) {
  try {
    const notes = this.form.habits[habitId]?.notes;
    if (notes) {
      const data = JSON.parse(notes);
      return data.foods || [];
    }
  } catch (e) {
    return [];
  }
  return [];
}
```

## 🧪 **Testing Scenarios**

### **Basic Functionality:**
1. ✅ Click "Makan Sehat" card → Modal opens
2. ✅ Type food name → Add to list with green tag
3. ✅ Press Enter → Quick add functionality
4. ✅ Click X on food → Remove from list
5. ✅ Complete with foods → Card shows green tags
6. ✅ Click Cancel → Modal closes, no changes

### **Multiple Food Input:**
1. ✅ Add "Salad" → Shows green tag
2. ✅ Add "Buah-buahan" → Multiple tags displayed
3. ✅ Add "Sayur rebus" → All tags visible
4. ✅ Remove middle item → List updates correctly
5. ✅ Complete → All foods saved to card display

### **Data Persistence:**
1. ✅ Add foods → Save form → Reload page → Foods still shown
2. ✅ Multiple foods → All displayed as green tags
3. ✅ Edit existing → Modify list → Changes persist

## 📁 **Files Modified**

### **Primary Changes:**
- `app/Views/siswa/habits/index.php`
  - ✅ Added healthy food modal state variables
  - ✅ Enhanced toggleHabit function with food modal trigger
  - ✅ Added healthy food management functions
  - ✅ Created healthy food modal HTML structure
  - ✅ Updated card click behavior for food cards
  - ✅ Modified toggle switch logic for food cards
  - ✅ Added healthy food display section in card

### **Key Functions Added:**
- ✅ `addHealthyFood()` - Add food to list
- ✅ `removeHealthyFood(index)` - Remove specific food
- ✅ `handleHealthyFoodComplete()` - Save and complete
- ✅ `closeHealthyFoodModal()` - Close modal
- ✅ `getHealthyFoodList(habitId)` - Get food data
- ✅ Enhanced `initializeExerciseList()` with food initialization

## 🚀 **Usage Instructions**

### **For Healthy Food Input:**
1. **Locate** "Makan Sehat" card (green themed with heart/food icon)
2. **Click** anywhere on the card
3. **Type** jenis makanan sehat (contoh: "Salad")
4. **Press Enter** atau click + button
5. **Repeat** untuk multiple foods
6. **Click "Selesai Makan Sehat"** untuk complete
7. **View** green food tags di card

### **Example Food List:**
- 🥗 Salad
- 🍎 Buah-buahan  
- 🥬 Sayur rebus
- 🥤 Smoothie
- 🐟 Ikan bakar
- 🥕 Wortel kukus

### **Visual Experience:**
- **Green Color Scheme**: Consistent dengan tema healthy food
- **Smooth Animations**: Modal entrance/exit dengan blur backdrop
- **Responsive Design**: Works pada semua screen sizes
- **Touch Friendly**: Easy interaction pada mobile devices

---

## ✅ **Status: FEATURE COMPLETED**

**Feature**: Interactive Healthy Food Modal
**Trigger**: Click on "Makan Sehat" card
**Question**: "Apa menu makan sehat mu hari ini?"
**Behavior**: Multiple input dengan green tags display
**Result**: Enhanced nutrition tracking dengan engaging interaction 🥗

**Test URL**: `http://localhost:8080/siswa/habits`
**Test Action**: Click pada card "Makan Sehat" untuk melihat modal!

---

## 📊 **Modal Comparison**

| Feature | Wake Up | Exercise | Healthy Food |
|---------|---------|----------|--------------|
| **Theme** | Amber/Orange | Rose/Pink | Green/Emerald |
| **Icon** | Sun ☀️ | Dumbbell 💪 | Salad 🥗 |
| **Input Type** | Time Picker | Multiple Text | Multiple Text |
| **Display** | Time Value | Exercise Tags | Food Tags |
| **Color Scheme** | Warm | Energetic | Fresh |
| **Completion** | Single Action | Multiple Items | Multiple Items |

**Result**: Consistent modal system dengan tema yang sesuai untuk setiap jenis kebiasaan! 🎉
