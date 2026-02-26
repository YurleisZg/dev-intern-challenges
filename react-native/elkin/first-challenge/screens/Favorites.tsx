// screens/Favorites.tsx
import { FlatList, View, Text, TouchableOpacity } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { useFavoritesStore } from '../store/useFavoriteStore';
import ProductCard from '../components/ProductCard';
import { useTheme } from '../context/ThemeContext';

import { useNavigation } from '@react-navigation/native';
import RemoveFavoriteModal from '../components/RemoveFavoriteModal';

const FavoritesScreen = () => {
    const { colors, isDark } = useTheme();
    // Get the favorites list directly from the store
    const favorites = useFavoritesStore((state) => state.favorites);
    const toggleFavorite = useFavoritesStore((state) => state.toggleFavorite);
    const isFavorite = useFavoritesStore((state) => state.isFavorite);
    const navigation = useNavigation<any>();

    return (
        <View className="flex-1 pt-[90px] px-4 pb-4" style={{ backgroundColor: colors.background }}>
            {favorites.length === 0 ? (
                <Text className="text-center mt-5" style={{ color: colors.text }}>
                    No favorites yet
                </Text>
            ) : (
                <FlatList
                    data={favorites}
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
            )}
            <RemoveFavoriteModal />
        </View>
    );
};

export default FavoritesScreen;