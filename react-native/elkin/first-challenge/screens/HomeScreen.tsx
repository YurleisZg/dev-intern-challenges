import React, { useLayoutEffect } from "react";
import { FlatList, View, TouchableOpacity, Text } from "react-native";
import ProductCard from "../components/ProductCard";
import { useTheme } from "../context/ThemeContext";
import { useFavoritesStore } from "../store/useFavoriteStore";
import { Ionicons } from "@expo/vector-icons";

import { useNavigation } from "@react-navigation/native";
import RemoveFavoriteModal from "../components/RemoveFavoriteModal";

const HomeScreen = () => {
    const { colors, isDark } = useTheme();
    const favorites = useFavoritesStore((state) => state.favorites);
    const toggleFavorite = useFavoritesStore((state) => state.toggleFavorite);
    const isFavorite = (id: number) => favorites.some(item => item.id === id);
    const navigation = useNavigation<any>();

    return (
        <>
            <View className="flex-1 pt-[90px] px-4 pb-4" style={{ backgroundColor: colors.background }}>
                {/* FlatList con altura fija */}
                <View className="flex-[2]">
                    <FlatList
                        data={[
                            { id: 1, title: "Card 1", description: "Descripción 1", price: 100 },
                            { id: 2, title: "Card 2", description: "Descripción 2", price: 100 },
                            { id: 3, title: "Card 3", description: "Descripción 3", price: 100 },
                        ]}
                        keyExtractor={(item) => item.id.toString()}
                        renderItem={({ item }) => (
                            <TouchableOpacity onPress={() => navigation.navigate("ProductDetails", { item })} activeOpacity={0.9}>
                                <View>
                                    <ProductCard title={item.title} description={item.description} price={item.price} />
                                    <TouchableOpacity
                                        onPress={() => toggleFavorite(item)}
                                        className={`absolute right-[30px] top-[30px] z-10 rounded-full p-2 shadow-md ${isDark ? 'bg-[rgba(30,30,30,0.8)]' : 'bg-[rgba(255,255,255,0.9)]'}`}
                                    >
                                        <Ionicons
                                            name={isFavorite(item.id) ? "heart" : "heart-outline"}
                                            size={22}
                                            color={isFavorite(item.id) ? "red" : colors.text}
                                        />
                                    </TouchableOpacity>
                                </View>
                            </TouchableOpacity>
                        )}
                    />
                </View>
            </View>
            <RemoveFavoriteModal />
        </>
    );
};

export default HomeScreen;