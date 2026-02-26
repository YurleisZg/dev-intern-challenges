import React from 'react';
import { TouchableOpacity, Text, TouchableOpacityProps } from 'react-native';
import { useTheme } from '../context/ThemeContext';

interface ButtonProps extends TouchableOpacityProps {
    title: string;
    variant?: 'primary' | 'secondary';
}

const CustomButton = ({ title, style, variant = 'primary', ...props }: ButtonProps) => {
    const { colors } = useTheme();

    return (
        <TouchableOpacity
            className="py-3 px-5 rounded-lg items-center justify-center my-2"
            style={[
                {
                    backgroundColor: variant === 'primary' ? colors.buttonBackground : 'transparent',
                    borderColor: colors.buttonBackground,
                    borderWidth: variant === 'secondary' ? 1 : 0
                },
                style,
            ]}
            activeOpacity={0.7}
            {...props}
        >
            <Text className="text-base font-bold" style={{ color: variant === 'primary' ? colors.buttonText : colors.buttonBackground }}>
                {title}
            </Text>
        </TouchableOpacity>
    );
};

export default CustomButton;
