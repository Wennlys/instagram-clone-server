import { screen } from '@testing-library/dom';
import { cleanup } from '@testing-library/react';
import React from 'react';
import Home from '.';
import api from '../../services/api';
import { postsResponseMock, renderWithRouter } from '../../utils/test.utils';

jest.mock('../../services/api');
const mockedApi = api as jest.Mocked<typeof api>;

afterEach(() => {
  cleanup();
  jest.resetAllMocks();
});

describe('Home rendering', () => {
  test('home component renders correctly', () => {
    mockedApi.get.mockImplementation(() => postsResponseMock.get.success);
    renderWithRouter(<Home />);
    expect(screen.getByText(/Home/i)).toBeInTheDocument();
  });

  it("renders 'loadPosts' component", () => {
    mockedApi.get.mockImplementation(() => postsResponseMock.get.success);
    renderWithRouter(<Home />);
    expect(screen.getByText(/PostList/i)).toBeInTheDocument();
  });

  it('loads at least one post', () => {
    mockedApi.get.mockImplementation(() => postsResponseMock.get.success);
    renderWithRouter(<Home />);
    expect(screen.getByTitle(/postlist/i)).toBeInTheDocument();
  });
});
