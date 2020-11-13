import { fireEvent, screen } from '@testing-library/dom';
import { render } from '@testing-library/react';
import React from 'react';
import Login from '.';
describe('Login rendering', () => {
  test('login component renders correctly', () => {
    render(<Login />);
    fireEvent.click(screen.getByText(/Log In/));
  });
});
