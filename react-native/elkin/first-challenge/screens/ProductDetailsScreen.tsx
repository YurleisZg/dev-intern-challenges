import React from "react";
import { View, Text, Image, TouchableOpacity, ScrollView } from "react-native";
import { Ionicons } from "@expo/vector-icons";
import { useRoute, useNavigation } from "@react-navigation/native";
import { useTheme } from "../context/ThemeContext";
import { useFavoritesStore } from "../store/useFavoriteStore";
import { useSafeAreaInsets } from "react-native-safe-area-context";
import RemoveFavoriteModal from "../components/RemoveFavoriteModal";

const ProductDetailsScreen = () => {
    const route = useRoute();
    const navigation = useNavigation();
    const { colors, isDark } = useTheme();
    const { item } = route.params as { item: any };

    const favorites = useFavoritesStore((state) => state.favorites);
    const toggleFavorite = useFavoritesStore((state) => state.toggleFavorite);
    const isFavorite = favorites.some((fav) => fav.id === item.id);
    const insets = useSafeAreaInsets();

    return (
        <View className="flex-1" style={{ backgroundColor: colors.background }}>
            {/* Custom Header Area (over the image) */}
            <View className="absolute top-12 left-0 right-0 z-10 px-4 flex-row justify-between items-center">
                <TouchableOpacity
                    onPress={() => navigation.goBack()}
                    className="flex-row items-center pt-2 pb-2 pr-4"
                >
                    <Ionicons name="chevron-back" size={24} color="#2b8a3e" />
                    <Text className="text-[#2b8a3e] font-bold text-lg ml-1">Catalogo</Text>
                </TouchableOpacity>

                <TouchableOpacity
                    onPress={() => toggleFavorite(item)}
                    className="bg-white rounded-full p-2 shadow-sm"
                >
                    <Ionicons
                        name={isFavorite ? "heart" : "heart-outline"}
                        size={24}
                        color={isFavorite ? "red" : "#666"}
                    />
                </TouchableOpacity>
            </View>

            <ScrollView className="flex-1" bounces={false} showsVerticalScrollIndicator={false}>
                {/* Hero Image */}
                <Image
                    source={{ uri: item.image || "https://picsum.photos/600/400" }}
                    className="w-full h-[400px]"
                    resizeMode="cover"
                />

                {/* Details Container */}
                <View
                    className="flex-1 -mt-8 pt-8 px-6 pb-24 rounded-t-3xl"
                    style={{ backgroundColor: colors.background }}
                >
                    {/* Header: Title and Price */}
                    <View className="flex-row justify-between items-start mb-1">
                        <Text className="text-2xl font-bold flex-1 mr-4" style={{ color: colors.text }}>
                            {item.title}
                        </Text>
                        <Text className="text-2xl font-bold text-[#2b8a3e]">
                            ${item.price.toFixed(2)}
                        </Text>
                    </View>

                    {/* Category */}
                    <Text className="text-base mb-6 text-gray-500">
                        {item.category || "Accesorios"}
                    </Text>

                    {/* Description Section */}
                    <Text className="text-lg font-bold mb-3" style={{ color: colors.text }}>
                        Descripcion
                    </Text>
                    <Text className="text-base leading-6 mb-8 text-gray-500">
                        {item.description || "Mochila de cuero sintetico resistente al agua con multiples compartimentos. Perfecta para el dia a dia, con espacio para laptop de hasta 15 pulgadas, bolsillos internos organizadores y correas acolchadas para maxima comodidad."}
                    </Text>

                    {/* Features Row */}
                    <View className="flex-row justify-between mb-8">
                        <View className="flex-1 bg-white items-center justify-center p-3 rounded-xl mx-1 border border-gray-100 shadow-sm" style={{ backgroundColor: isDark ? '#222' : '#fff', borderColor: colors.border }}>
                            <Ionicons name="shield-checkmark-outline" size={24} color="#2b8a3e" className="mb-1" />
                            <Text className="text-xs text-center mt-1" style={{ color: colors.text }}>Garantia</Text>
                        </View>
                    </View>
                </View>
            </ScrollView>

            {/* Sticky Bottom Button */}
            <View
                className="absolute left-0 right-0 p-4 border-t"
                style={{
                    backgroundColor: colors.background,
                    borderColor: colors.border,
                    bottom: 0,
                    paddingBottom: Math.max(16, insets.bottom)
                }}
            >
                <TouchableOpacity
                    className="bg-[#2b8a3e] flex-row items-center justify-center py-4 rounded-xl"
                    activeOpacity={0.8}
                >
                    <Ionicons name="cart-outline" size={20} color="white" className="mr-2" />
                    <Text className="text-white font-bold text-lg ml-2">Agregar</Text>
                </TouchableOpacity>
            </View>

            <RemoveFavoriteModal />
        </View>
    );
};

export default ProductDetailsScreen;
