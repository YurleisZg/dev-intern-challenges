import { View, Text, Image } from 'react-native';
import { useTheme } from '../context/ThemeContext';

const Card = ({ title, description, price }: { title: string, description: string, price: number }) => {
    const { colors } = useTheme();

    return (
        <View className="rounded-lg p-4 m-4 shadow-sm border" style={{ backgroundColor: colors.card, borderColor: colors.border }}>
            <Image
                className="w-full h-[150px] rounded-lg"
                source={{ uri: "https://picsum.photos/300/200" }}
            />
            <Text className="text-lg font-bold mt-2.5" style={{ color: colors.text }}>{title}</Text>
            <Text className="text-lg font-bold mt-2.5" style={{ color: colors.price }}>${price}</Text>
            <Text className="text-sm mt-1" style={{ color: colors.description }}>
                {description}
            </Text>
        </View>
    )
}

export default Card;
