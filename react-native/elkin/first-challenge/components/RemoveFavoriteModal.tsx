import React from 'react';
import { Modal, View, Text, Image, TouchableOpacity, TouchableWithoutFeedback } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { useFavoritesStore } from '../store/useFavoriteStore';
import { useTheme } from '../context/ThemeContext';

import { StatusBar } from 'expo-status-bar';

const RemoveFavoriteModal = () => {
    const { colors, isDark } = useTheme();
    const { itemToRemove, isModalVisible, confirmRemoveFavorite, cancelRemoveFavorite } = useFavoritesStore();

    if (!itemToRemove) return null;

    return (
        <Modal
            animationType="slide"
            transparent={true}
            visible={isModalVisible}
            onRequestClose={cancelRemoveFavorite}
        >
            <StatusBar style="light" backgroundColor="rgba(0,0,0,0.4)" translucent={true} />
            <TouchableWithoutFeedback onPress={cancelRemoveFavorite}>
                <View className="flex-1 justify-end bg-black/40">
                    <TouchableWithoutFeedback>
                        <View
                            className="pt-4 pb-8 px-6 rounded-t-3xl items-center"
                            style={{ backgroundColor: colors.background }}
                        >
                            {/* Drag Indicator */}
                            <View className="w-12 h-1 bg-gray-300 rounded-full mb-6" />

                            {/* Icon Circle */}
                            <View className="w-16 h-16 rounded-full bg-red-50 flex items-center justify-center mb-4">
                                <Ionicons name="heart-dislike-outline" size={28} color="#f87171" className="ml-[2px]" />
                            </View>

                            {/* Title */}
                            <Text className="text-xl font-bold mb-6" style={{ color: colors.text }}>
                                Quitar de Favoritos?
                            </Text>

                            {/* Item Summary Card */}
                            <View
                                className="w-full flex-row items-center p-3 rounded-2xl border mb-6"
                                style={{ backgroundColor: colors.card, borderColor: colors.border }}
                            >
                                <Image
                                    source={{ uri: itemToRemove.image || "https://picsum.photos/300/200" }}
                                    className="w-16 h-16 rounded-xl mr-4"
                                />
                                <View className="flex-1">
                                    <Text className="text-base font-bold mb-1" style={{ color: colors.text }}>
                                        {itemToRemove.title}
                                    </Text>
                                    <Text className="text-sm text-gray-500">
                                        <Text className="font-bold text-gray-500">${itemToRemove.price.toFixed(2)}</Text> • {itemToRemove.category || 'Accesorios'}
                                    </Text>
                                </View>
                            </View>

                            <Text className="text-sm text-center mb-6 px-4 leading-5 text-gray-500">
                                Este articulo sera removido de tu lista de favoritos.
                            </Text>

                            {/* Confirm Button */}
                            <TouchableOpacity
                                onPress={confirmRemoveFavorite}
                                className="w-full bg-[#d98c76] py-4 rounded-2xl flex-row justify-center items-center mb-3"
                                activeOpacity={0.8}
                            >
                                <Ionicons name="trash-outline" size={20} color="white" className="mr-2" />
                                <Text className="text-white font-bold text-base ml-2">Si, quitar de favoritos</Text>
                            </TouchableOpacity>

                            {/* Cancel Button */}
                            <TouchableOpacity
                                onPress={cancelRemoveFavorite}
                                className="w-full py-4 rounded-2xl flex-row justify-center items-center border border-gray-200"
                                style={{ backgroundColor: colors.card, borderColor: colors.border }}
                                activeOpacity={0.6}
                            >
                                <Text className="font-bold text-base" style={{ color: colors.text }}>Cancerlar</Text>
                            </TouchableOpacity>
                        </View>
                    </TouchableWithoutFeedback>
                </View>
            </TouchableWithoutFeedback>
        </Modal>
    );
};

export default RemoveFavoriteModal;
