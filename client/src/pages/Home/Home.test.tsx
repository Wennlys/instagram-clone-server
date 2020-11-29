import { screen } from '@testing-library/react';
import React from 'react';
import { act } from 'react-dom/test-utils';
import Home from '.';
import api from '../../services/api';
import { postsResponseMock, renderWithRouter } from '../../utils/test.utils';

jest.mock('../../services/api');
const mockedApi = api as jest.Mocked<typeof api>;

afterEach(() => {
  jest.resetAllMocks();
});

describe('Home rendering', () => {
  test('home component renders correctly', async () => {
    mockedApi.get.mockImplementation(() => Promise.resolve(postsResponseMock.get.success));
    await act(async () => {
      renderWithRouter(<Home />);
      expect(screen.getByText(/Home/i)).toBeInTheDocument();
      expect(screen.getByText(/PostList/i)).toBeInTheDocument();
      expect(screen.getByTitle(/postlist/i)).toBeInTheDocument();
    });
  });
});
