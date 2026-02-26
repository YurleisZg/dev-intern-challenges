import "./global.css";
import { NavigationContainer } from "@react-navigation/native";
import { createNativeStackNavigator } from "@react-navigation/native-stack";
import { createBottomTabNavigator } from '@react-navigation/bottom-tabs';
import { StatusBar } from "expo-status-bar";
import { Ionicons } from '@expo/vector-icons';
import HomeScreen from "./screens/HomeScreen";
import TestScreen from "./screens/Favorites";
import { TouchableOpacity, Text } from "react-native";
import { ThemeProvider, useTheme } from "./context/ThemeContext";

const Stack = createNativeStackNavigator();
const Tab = createBottomTabNavigator();

function TabNavigator() {
  const { colors, isDark, toggleTheme } = useTheme();

  return (
    <Tab.Navigator
      screenOptions={{
        headerTransparent: true,
        headerTitleAlign: "left",
        headerTintColor: colors.text,
        tabBarStyle: { backgroundColor: colors.tabBar },
        tabBarActiveTintColor: colors.tint,
        tabBarInactiveTintColor: '#888',
        headerRight: () => (
          <TouchableOpacity
            onPress={toggleTheme}
            className={`mr-4 p-2 rounded-full ${isDark ? 'bg-[#333]' : 'bg-[#eee]'}`}
          >
            <Text className="text-base">{isDark ? "☀️" : "🌙"}</Text>
          </TouchableOpacity>
        ),
      }}
    >
      <Tab.Screen
        name="HomeScreen"
        component={HomeScreen}
        options={{
          headerTitle: "MiniCatalog",
          tabBarLabel: "Home",
          tabBarIcon: ({ color, size }) => (
            <Ionicons name="home-outline" size={size} color={color} />
          )
        }}
      />
      <Tab.Screen
        name="Favorites"
        component={TestScreen}
        options={{
          headerTitle: "My Favorites",
          tabBarLabel: "Favorites",
          tabBarIcon: ({ color, size }) => (
            <Ionicons name="heart-outline" size={size} color={color} />
          )
        }}
      />
    </Tab.Navigator>
  );
}

import ProductDetailsScreen from "./screens/ProductDetailsScreen";

function Navigation() {
  const { isDark } = useTheme();

  return (
    <NavigationContainer>
      <StatusBar style={isDark ? "light" : "dark"} />
      <Stack.Navigator screenOptions={{ headerShown: false }}>
        <Stack.Screen name="Tabs" component={TabNavigator} />
        <Stack.Screen
          name="ProductDetails"
          component={ProductDetailsScreen}
          options={{ presentation: 'fullScreenModal' }}
        />
      </Stack.Navigator>
    </NavigationContainer>
  );
}

export default function App() {
  return (
    <ThemeProvider>
      <Navigation />
    </ThemeProvider>
  );
}